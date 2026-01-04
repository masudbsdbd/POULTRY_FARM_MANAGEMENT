<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Quotation;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Invoice;
use App\Models\Activitylog;

use App\Models\QuotationItem;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Bank;
use App\Models\FloorInfo;
use App\Models\Approval;
use App\Models\approval_items;
use Illuminate\Support\Facades\Hash;

class ApprovalController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:stock-list', ['only' => ['todayStock', 'monthStock', 'stockItems', 'detail', 'manageStock', 'manageStockItems']]);
    }

    public function index($quotationId)
    {
        $pageTitle = 'Manage Approvals';
        $approvals = Approval::where("quotation_id", $quotationId)->latest()->paginate(10);
        $quotationsInfo = Quotation::where("id", $quotationId)->first();
        return view("quotation.approval", compact('quotationId', 'approvals', 'pageTitle', 'quotationsInfo'));
    }


    public function manageApprovalCreate($quotationId)
    {
        $approvalItems = ChallanItem::with("floor.building")->select(
            'challan_items.floor_id',
            'challan_items.product_id',
            'products.name as product_name',
            DB::raw('SUM(challan_items.quantity) as total_quantity'),
            DB::raw('SUM(challan_items.approved_qty) as total_approved'),
            DB::raw('SUM(challan_items.quantity - challan_items.approved_qty) as pending_qty')
        )
            ->leftJoin('products', 'products.id', '=', 'challan_items.product_id')
            ->leftJoin('challans', 'challans.id', '=', 'challan_items.challan_id')
            ->whereRaw('(challan_items.quantity - challan_items.approved_qty) > 0')
            ->where('challans.quotation_id', $quotationId)
            ->groupBy('challan_items.floor_id', 'challan_items.product_id', 'products.name') // <-- fix
            ->get()
            ->groupBy('product_id');


        // dd($approvalItems->toArray());
        $lastId = Approval::max('id');
        $approvalNumber = $this->generateApprovalNumber($lastId);

        $pageTitle = 'Create Approvals';
        $quotationsInfo = Quotation::where("id", $quotationId)->first();

        return view('quotation.createApproval', compact('pageTitle', 'quotationsInfo', 'approvalItems', 'approvalNumber'));
    }

    function generateApprovalNumber($lastId)
    {
        $nextId = $lastId + 1;
        return "Ap-" . str_pad($nextId, 5, "0", STR_PAD_LEFT);
    }






    public function manageApprovalStore(Request $request)
    {
        if ($request->has('products')) {
            DB::transaction(function () use ($request) {

                $input = $request->all();
                $quotation_id = $input['quotation_id'];

                // // insert approval info
                $approval = new Approval();
                $approval->approval_number = $input['approval_number'];
                $approval->approval_date = $input['approval_date'];
                if ($request->hasFile('diagram_image')) {
                    $fileName = time() . '.' . $request->diagram_image->extension();
                    $request->diagram_image->move(public_path('uploads/approval'), $fileName);
                    $approval->diagram_image = $fileName;
                }
                $approval->notes = $input['notes'];
                $approval->quotation_id = $input['quotation_id'];
                $approval->save();

                // floor wise approval items array
                $approvalItems = [];
                if (isset($input['products'])) {
                    foreach ($input['products'] as $key => $product) {
                        $approvalItems[$input['floor_id'][$key]][] = [
                            'product_id' => $product,
                            'floor_id' => $input['floor_id'][$key],
                            'pending_qty' => $input['qty'][$key],
                        ];
                    }
                }

                foreach ($approvalItems as $floorId => $items) {
                    // insert approval items
                    foreach ($items as $item) {
                        // update Quotation Item approved qty
                        $quotationItem = QuotationItem::where('quotation_id', $quotation_id)->where('product_id', $item['product_id'])->first();
                        $quotationItem->approved_qty = $quotationItem->approved_qty + $item['pending_qty'];
                        $quotationItem->save();

                        // dd($item);
                        $approvalItem = new approval_items();
                        $approvalItem->approval_id = $approval->id;
                        $approvalItem->product_id = $item['product_id'];
                        $approvalItem->floor_id = $item['floor_id'];
                        $approvalItem->approved_qty = $item['pending_qty'];
                        $approvalItem->save();

                        // update challan item approved qty
                        $challanItems = ChallanItem::select(
                            'challan_items.id',
                            'challan_items.product_id',
                            'challan_items.approved_qty',
                            DB::raw('SUM(challan_items.quantity - challan_items.approved_qty) as pending_qty')
                        )
                            ->leftJoin('challans', 'challans.id', '=', 'challan_items.challan_id')
                            ->whereRaw('(challan_items.quantity - challan_items.approved_qty) > 0')
                            ->where('challans.quotation_id', $quotation_id)
                            ->where('challan_items.product_id', $item['product_id'])
                            ->where('challan_items.floor_id', $item['floor_id'])
                            ->groupBy('challan_items.id', 'challan_items.product_id', 'challan_items.approved_qty')
                            ->get();
                        // dd($challanItems->toArray());
                        foreach ($challanItems as $challanItem) {
                            if ($item['pending_qty'] > 0) {
                                $availableQty = $challanItem->pending_qty;
                                if ($item['pending_qty'] >= $availableQty) {
                                    // update full available qty
                                    $challanItem->approved_qty += $availableQty;
                                    $item['pending_qty'] -= $availableQty;
                                } else {
                                    // update partial qty
                                    $challanItem->approved_qty += $item['pending_qty'];
                                    $item['pending_qty'] = 0;
                                }
                                $challanItem->save();
                            }
                        }
                    }
                }
            });

            $notify[] = ['success', 'Approval has been Created successfully'];
            return to_route('approval.all', $request->quotation_id)->withNotify($notify);
        } else {
            $notify[] = ['error', 'Product not available to approve.'];
            return to_route('approval.all', $request->quotation_id)->withNotify($notify);
        }
    }


    public function updateApproval(Request $request, $id)
    {
        $approvalInfo = Approval::find($id);

        $approvalItems = approval_items::with('product')
            ->where("approval_id", $id)
            ->get()
            ->groupBy('floor_id');

        // প্রতিটি approval item এর সাথে available qty যোগ করি
        foreach ($approvalItems as $floorId => $items) {
            foreach ($items as $item) {
                $pendingQty = ChallanItem::select(DB::raw('SUM(quantity - approved_qty) as pending_qty'))
                    ->join('challans', 'challans.id', '=', 'challan_items.challan_id')
                    ->where('challans.quotation_id', $approvalInfo->quotation_id)
                    ->where('challan_items.product_id', $item->product_id)
                    ->where('challan_items.floor_id', $item->floor_id)
                    ->value('pending_qty');

                // available_qty = approved + pending
                $item->available_qty = ($pendingQty ?? 0) + $item->approved_qty;
            }
        }

        $quotationsInfo = Quotation::where("id", $approvalInfo->quotation_id)->first();
        $pageTitle = 'Update Approval';

        return view('quotation.updateApproval', compact('pageTitle', 'approvalInfo', 'approvalItems', 'quotationsInfo'));
    }


    public function updateApprovalDb(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $input = $request->all();

            $approval = Approval::findOrFail($id);
            $approval->approval_date = $input['approval_date'];
            if ($request->hasFile('diagram_image')) {
                $fileName = time() . '.' . $request->diagram_image->extension();
                $request->diagram_image->move(public_path('uploads/approval'), $fileName);
                $approval->diagram_image = $fileName;
            }
            $approval->notes = $input['notes'];
            $approval->save();

            $quotation_id = $input['quotation_id'];

            // loop through products
            foreach ($input['products'] as $key => $productId) {
                $floorId = $input['floor_id'][$key];
                $newQty  = (int) $input['qty'][$key];

                // 1. approval_items row খুঁজে বের করো
                $approvalItem = approval_items::where('approval_id', $approval->id)
                    ->where('product_id', $productId)
                    ->where('floor_id', $floorId)
                    ->first();

                $oldQty = $approvalItem ? $approvalItem->approved_qty : 0;

                // qty difference বের করো
                $diff = $newQty - $oldQty;

                if ($diff == 0) {
                    continue; // কিছু পরিবর্তন নাই
                }

                // 2. যদি qty = 0 হয় → delete
                if ($newQty == 0 && $approvalItem) {
                    $approvalItem->delete();

                    // quotation item কমাও
                    $quotationItem = QuotationItem::where('quotation_id', $quotation_id)
                        ->where('product_id', $productId)
                        ->first();
                    if ($quotationItem) {
                        $quotationItem->approved_qty -= $oldQty;
                        $quotationItem->save();
                    }

                    // challan item থেকে approved qty release করতে হবে
                    $this->releaseChallanQty($quotation_id, $productId, $floorId, $oldQty);

                    continue;
                }

                // 3. যদি নতুন qty থাকে
                if ($approvalItem) {
                    $approvalItem->approved_qty = $newQty;
                    $approvalItem->save();
                } else {
                    $approvalItem = new approval_items();
                    $approvalItem->approval_id = $approval->id;
                    $approvalItem->product_id = $productId;
                    $approvalItem->floor_id = $floorId;
                    $approvalItem->approved_qty = $newQty;
                    $approvalItem->save();
                }

                // 4. quotation_items update
                $quotationItem = QuotationItem::where('quotation_id', $quotation_id)
                    ->where('product_id', $productId)
                    ->first();
                if ($quotationItem) {
                    $quotationItem->approved_qty += $diff;
                    $quotationItem->save();
                }

                // 5. challan_items update
                if ($diff > 0) {
                    // নতুন qty বাড়ালে challan item এ allocate করতে হবে
                    $this->allocateChallanQty($quotation_id, $productId, $floorId, $diff);
                } else {
                    // qty কমালে challan item থেকে release করতে হবে
                    $this->releaseChallanQty($quotation_id, $productId, $floorId, abs($diff));
                }
            }
        });
        $activitylog = new Activitylog();
        $activitylog->user_id = auth()->user()->id ?? null;
        $activitylog->action_type = 'EDIT';
        $activitylog->table_name = 'approvals';
        $activitylog->record_id = $id ?? null;
        $activitylog->ip_address = session()->get('ip_address');
        $activitylog->remarks = 'approvals has been edited';
        $activitylog->timestamp = now();
        $activitylog->save();
        $notify[] = ['success', 'Approval updated successfully'];
        return to_route('approval.all', $request->quotation_id)->withNotify($notify);
    }


    public function deleteApproval(Request $request,$id)
    {
         if(!Hash::check($request->password, auth()->user()->password)){
            $notify[] = ['error', 'Incorrect password. Deletion cancelled.'];
            return back()->withNotify($notify);
        }
        DB::transaction(function () use ($id) {
            $approval = Approval::findOrFail($id);

            $quotation_id = $approval->quotation_id;

            // approval_items গুলো বের করি
            $approvalItems = approval_items::where('approval_id', $id)->get();

            foreach ($approvalItems as $item) {
                $productId = $item->product_id;
                $floorId   = $item->floor_id;
                $approvedQty = $item->approved_qty;

                // 1. quotation_items এ adjust করো
                $quotationItem = QuotationItem::where('quotation_id', $quotation_id)
                    ->where('product_id', $productId)
                    ->first();

                if ($quotationItem) {
                    $quotationItem->approved_qty -= $approvedQty;
                    if ($quotationItem->approved_qty < 0) {
                        $quotationItem->approved_qty = 0;
                    }
                    $quotationItem->save();
                }

                // 2. challan_items থেকে release করো
                $this->releaseChallanQty($quotation_id, $productId, $floorId, $approvedQty);

                // 3. approval_items থেকে delete করো
                $item->delete();
            }

            // শেষে approval delete করো
            $approval->delete();
        });
        $activitylog = new Activitylog();
        $activitylog->user_id = auth()->user()->id ?? null;
        $activitylog->action_type = 'DELETE';
        $activitylog->table_name = 'approvals';
        $activitylog->record_id = $id ?? null;
        $activitylog->ip_address = session()->get('ip_address');
        $activitylog->remarks = 'approvals has been deleted';
        $activitylog->timestamp = now();
        $activitylog->save();
        return redirect()->back()->with('success', 'Approval deleted successfully');
    }




    private function allocateChallanQty($quotation_id, $productId, $floorId, $qty)
    {
        $challanItems = ChallanItem::whereHas('challan', function ($q) use ($quotation_id) {
            $q->where('quotation_id', $quotation_id);
        })
            ->where('product_id', $productId)
            ->where('floor_id', $floorId)
            ->orderBy('id')
            ->get();

        foreach ($challanItems as $item) {
            $pending = $item->quantity - $item->approved_qty;
            if ($pending <= 0) continue;

            if ($qty >= $pending) {
                $item->approved_qty += $pending;
                $qty -= $pending;
            } else {
                $item->approved_qty += $qty;
                $qty = 0;
            }
            $item->save();

            if ($qty <= 0) break;
        }
    }

    private function releaseChallanQty($quotation_id, $productId, $floorId, $qty)
    {
        $challanItems = ChallanItem::whereHas('challan', function ($q) use ($quotation_id) {
            $q->where('quotation_id', $quotation_id);
        })
            ->where('product_id', $productId)
            ->where('floor_id', $floorId)
            ->orderByDesc('id') // শেষের দিক থেকে release করা ভাল
            ->get();

        foreach ($challanItems as $item) {
            if ($item->approved_qty <= 0) continue;

            if ($qty >= $item->approved_qty) {
                $qty -= $item->approved_qty;
                $item->approved_qty = 0;
            } else {
                $item->approved_qty -= $qty;
                $qty = 0;
            }
            $item->save();

            if ($qty <= 0) break;
        }
    }


    public function print($id)
    {
        $pageTitle = 'Print Approval';
        $mpdf = setPdf();

        // Approval info load
        $approvalInfo = Approval::with([
            'items.product',
            'items.product.unit',
            'items.floor.building', // floor relation add
        ])->where("id", $id)->first();

        $quotation = Quotation::where("id", $approvalInfo->quotation_id)->first();

        $organizedItems = [];

        // Product-wise grouping & floors concat
        foreach ($approvalInfo->items as $item) {
            $productId = $item->product_id;
            $floorName = $item->floor ? $item->floor->name : 'N/A';
            $buildingName = $item->floor->building ? $item->floor->building->name : 'N/A';
            if (!isset($organizedItems[$productId])) {
                $organizedItems[$productId] = [
                    'product_name' => $item->product->name,
                    'unit_name' => $item->product->unit ? $item->product->unit->name : '',
                    'total_qty' => 0,
                    'total_approved' => 0,
                    'building' => [],
                ];
            }

            $organizedItems[$productId]['total_qty'] += $item->quantity;
            $organizedItems[$productId]['total_approved'] += $item->approved_qty;

            if (!in_array($buildingName, $organizedItems[$productId]['building'])) {
                $organizedItems[$productId]['building'][$buildingName]['floors'][] = $floorName;
            }
        }



        // Floors array -> comma separated string
        foreach ($organizedItems as $productId => $data) {
            foreach ($data['building'] as $building => $floors) {
                $organizedItems[$productId]['building'][$building] = implode(', ', $floors['floors']);
            }
        }

        // dd($organizedItems);

        // Render view
        $html = view('quotation.printApproval', compact(
            'pageTitle',
            'approvalInfo',
            'organizedItems',
            'quotation'
        ))->render();

        $mpdf->WriteHTML($html);

        return response(
            $mpdf->Output('approval-' . $quotation->id . '.pdf', 'I')
        )->header('Content-Type', 'application/pdf');
    }


    public function approvedItems($quotationId, $product_id)
    {
        $approvalItems = approval_items::with('product', 'floor')
            // ->where('approval_id', Approval::where('quotation_id', $quotationId)->first()->id)
            ->leftJoin('approvals', 'approvals.id', '=', 'approval_items.approval_id')
            ->where('approvals.quotation_id', $quotationId)
            ->where('product_id', $product_id)
            ->paginate(10);
        $pageTitle = 'Approved Items';

        // dd($approvalItems);
        return view('quotation.approvedItems', compact('approvalItems', 'quotationId', 'pageTitle'));
    }
}
