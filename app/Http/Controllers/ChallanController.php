<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\Quotation;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Invoice;
use App\Models\PurchaseBatch;
use App\Models\ManageStock;
use App\Models\ManageStockItem;
use App\Models\Activitylog;


use App\Models\QuotationItem;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Bank;
use App\Models\Building;
use App\Models\FloorInfo;
use Illuminate\Support\Facades\Hash;

class ChallanController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:stock-list', ['only' => ['todayStock', 'monthStock', 'stockItems', 'detail', 'manageStock', 'manageStockItems']]);
    }

    public function index($quotationId)
    {
        $pageTitle = 'Manage Challans';
        $challans  = Challan::where("quotation_id", $quotationId)->latest('id')->paginate(gs()->pagination);
        $quotationsInfo = Quotation::where("id", $quotationId)->first();
        return view("quotation.challan", compact('quotationId', 'challans', 'pageTitle', 'quotationsInfo'));
    }

    public function manageChallanCreate($id)
    {
        $pageTitle = 'Add Work Progress';
        $quotations = Quotation::whereStatus(1)->latest()->get();
        $lastId = Challan::max('id');
        $challanNumber = $this->generateChallanNumber($lastId);

        $quatationId = $id;
        $quotationsInfo = Quotation::where("id", $quatationId)->first();
        $quotationItems = QuotationItem::with('product')->where("quotation_id", $quatationId)->get();
        $floors = FloorInfo::get();
        $buildings = Building::with("floor")->get();


        return view('quotation.create-challan', compact('pageTitle', 'quotations', 'challanNumber', 'quotationItems', 'quotationsInfo', 'floors', 'buildings'));
    }

    function generateChallanNumber($lastId)
    {
        $nextId = $lastId + 1;
        return "WP-" . str_pad($nextId, 5, "0", STR_PAD_LEFT);
    }

    public function manageChallanStore(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required|numeric',
            'challan_date' => 'required|date',
            'challan_number' => 'required|string',
            'products' => 'required|array|min:1',
            'qty' => 'required|array',
            'unitPrice' => 'required|array|min:1',
            'priceTotal' => 'required|array|min:1',
            'notes' => 'string|nullable',
            'floors' => 'array|nullable',
        ]);

        // dd($request->all());

        $input = $request->all();

        // dd($input);
        $challan = new Challan();
        $challan->challan_number = $input['challan_number'];
        $challan->challan_date = $input['challan_date'];
        $challan->quotation_id = $input['quotation_id'];
        $challan->total_amount = $input['calculatedPrice'];
        $challan->notes = $input['notes'];
        $challan->save();


        // update quotation used item amount
        $quotation = Quotation::where('id', $input['quotation_id'])->first();
        $quotation->used_product_amount += $input['calculatedPrice'];
        $quotation->save();

        if (isset($input['products'])) {
            foreach ($input['products'] as $key => $product) {
                if ($input['qty'][$key] > 0) {
                    $challanItem = new ChallanItem();
                    $challanItem->challan_id = $challan->id;
                    $challanItem->product_id = $product;
                    $challanItem->floor_id = $input['floors'][$key] ?? null;
                    // dd($input['qty'][$key] ?? null);
                    $challanItem->quantity = $input['qty'][$key];
                    $challanItem->unit_price = $input['unitPrice'][$key];
                    $challanItem->total = $input['priceTotal'][$key];
                    $challanItem->save();

                    // update quotation items
                    $quotationItem = QuotationItem::where('quotation_id', $input['quotation_id'])->where('product_id', $product)->first();
                    $quotationItem->used_qty = $quotationItem->used_qty + $input['qty'][$key];
                    $quotationItem->save();
                    // dd($quotationItem);
                }
            }
        }


        $message = 'Challan successfully added.';

        $notify[] = ['success', $message];
        return to_route('challan.all', $input['quotation_id'])->withNotify($notify);
    }


    public function manageChallanEdit(Request $request, $id)
    {
        $pageTitle = 'Manage Challan Create';
        $challan = Challan::find($id);
        $challanItems = ChallanItem::with('product', 'floor')->where('challan_id', $id)->get();
        // dd($challanItems);
        $quotationsInfo = Quotation::where("id", $challan->quotation_id)->first();
        $quotationItems = QuotationItem::with('product')->where("quotation_id", $challan->quotation_id)->get();

        $totalProductQty = ChallanItem::with('product')->where('challan_id', $id)->sum('quantity');
        $totalProductPrice = ChallanItem::with('product')->where('challan_id', $id)->sum(DB::raw("quantity * unit_price"));
        $floors = FloorInfo::get();
        // dd($totalProductPrice);
        $buildings = Building::with("floor")->get();

        // dd($challan, $challanItems);
        return view('quotation.create-challan', compact('pageTitle', 'challan', 'challanItems', 'quotationsInfo', 'quotationItems', 'totalProductQty', 'totalProductPrice', 'floors', 'buildings'));
    }

    
    public function challanDownload($id)
    {
        $mpdf = setPdf();
        $challan = Challan::find($id);
        $challanItems = ChallanItem::with('product', 'floor')->where('challan_id', $id)->get();
        $quotationsInfo = Quotation::where("id", $challan->quotation_id)->first();
        $floors = FloorInfo::get();
        $buildings = Building::with("floor")->get();
        $html = view('quotation.challan-pdf', compact('challan', 'challanItems', 'floors', 'buildings', 'quotationsInfo'))->render();
        $mpdf->WriteHTML($html);
        return response($mpdf->Output('challan-' . $challan->id . '.pdf', 'I'))->header('Content-Type', 'application/pdf');
    }
    
    public function manageChallanUpdate(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'quotation_id' => 'required|numeric',
            'challan_date' => 'required|date',
            'challan_number' => 'required|string',
            'products' => 'required|array|min:1',
            'qty' => 'required|array|min:1',
            'unitPrice' => 'required|array|min:1',
            'priceTotal' => 'required|array|min:1',
            'notes' => 'string|nullable',
            'floors' => 'array|nullable',
        ]);


        $challan = Challan::find($id);

        $input = $request->all();

        // update quotation used item amount
        $quotation = Quotation::where('id', $input['quotation_id'])->first();
        $quotation->used_product_amount = ($quotation->used_product_amount + $input['calculatedPrice']) - $challan->total_amount;
        $quotation->save();


        // dd($input);
        // $challan = new Challan();
        $challan->challan_number = $input['challan_number'];
        $challan->challan_date = $input['challan_date'];
        $challan->quotation_id = $input['quotation_id'];
        $challan->total_amount = $input['calculatedPrice'];
        $challan->notes = $input['notes'];
        $challan->save();





        // deleted product
        if (isset($input["deleltedProduct"])) {
            foreach ($input["deleltedProduct"] as $productIndex => $productId) {
                $challanItem = ChallanItem::where("product_id", $productId)->where('challan_id', $id)->first();
                // update quotation items
                $quotationItem = QuotationItem::where('quotation_id', $input['quotation_id'])->where('product_id', $productId)->first();
                $quotationItem->used_qty = $quotationItem->used_qty  - $challanItem->quantity;
                $quotationItem->save();

                // perform delete action
                $challanItem->delete();
            }
        }

        if (isset($input['products'])) {
            foreach ($input['products'] as $key => $product) {

                $challanItem = ChallanItem::where('challan_id', $id)->where("product_id", $product)->first();

                if (!$challanItem) {
                    $challanItem = new ChallanItem();
                }
                $oldQty = $challanItem->quantity;
                $challanItem->challan_id = $challan->id;
                $challanItem->product_id = $product;
                $challanItem->floor_id = $input['floors'][$key] ?? null;
                $challanItem->quantity = $input['qty'][$key];
                $challanItem->unit_price = $input['unitPrice'][$key];
                $challanItem->total = $input['priceTotal'][$key];
                $challanItem->save();

                if (!$challanItem) {
                    // update quotation items
                    $quotationItem = QuotationItem::where('quotation_id', $input['quotation_id'])->where('product_id', $product)->first();
                    $quotationItem->used_qty = $quotationItem->used_qty + $input['qty'][$key];
                    $quotationItem->save();
                    // dd($quotationItem);
                } else {
                    $quotationItem = QuotationItem::where('quotation_id', $input['quotation_id'])->where('product_id', $product)->first();
                    $quotationItem->used_qty = ($quotationItem->used_qty + $input['qty'][$key]) - $oldQty;
                    $quotationItem->save();
                }
            }
        }
        $activitylog = new Activitylog();
        $activitylog->user_id = auth()->user()->id ?? null;
        $activitylog->action_type = 'EDIT';
        $activitylog->table_name = 'challans';
        $activitylog->record_id = $id ?? null;
        $activitylog->ip_address = session()->get('ip_address');
        $activitylog->remarks = 'challans has been edited';
        $activitylog->timestamp = now();
        $activitylog->save();
        $message = 'Challan successfully edited.';
        $notify[] = ['success', $message];
        return to_route('challan.all', $input['quotation_id'])->withNotify($notify);
    }



    public function deleteChallan(Request $request,$challanId)
    {
         if(!Hash::check($request->password, auth()->user()->password)){
            $notify[] = ['error', 'Incorrect password. Deletion cancelled.'];
            return back()->withNotify($notify);
        }
        $challan = Challan::find($challanId);
        $quotationId = $challan->quotation_id;
        DB::transaction(function () use ($challanId, $challan) {
            $challanItem = ChallanItem::where('challan_id', $challanId)->get();
            // dd($challan, $challanItem);

            // update quotation used item amount
            $quotation = Quotation::where('id', $challan->quotation_id)->first();
            $quotation->used_product_amount = $quotation->used_product_amount - $challan->total_amount;
            $quotation->save();

            // deleted product
            if (isset($challanItem)) {
                foreach ($challanItem as $itemKey => $item) {
                    $challanItem = ChallanItem::where("id", $item->id)->first();
                    // update quotation items
                    $quotationItem = QuotationItem::where('quotation_id', $challan->quotation_id)->where('product_id', $item->product_id)->first();
                    $quotationItem->used_qty = $quotationItem->used_qty  - $challanItem->quantity;
                    $quotationItem->save();

                    // perform delete action
                    $challanItem->delete();
                }
            }

            $challan->delete();
        });
            $activitylog = new Activitylog();
            $activitylog->user_id = auth()->user()->id ?? null;
            $activitylog->action_type = 'DELETE';
            $activitylog->table_name = 'challans';
            $activitylog->record_id = $challanId ?? null;
            $activitylog->ip_address = session()->get('ip_address');
            $activitylog->remarks = 'challans has been deleted';
            $activitylog->timestamp = now();
            $activitylog->save();
        $message = 'Challan deleted successfylly.';
        $notify[] = ['success', $message];
        return to_route('challan.all', $quotationId)->withNotify($notify);
    }


    public function getChallanItems($quotationId, $productId)
    {
        $challanItems = ChallanItem::with("product")->select('challan_items.*', 'challans.quotation_id')
            ->leftJoin('challans', 'challans.id', '=', 'challan_items.challan_id')
            ->where('challans.quotation_id', $quotationId)
            ->where('challan_items.product_id', $productId)
            ->paginate(gs()->pagination);
        $quotationsInfo = Quotation::where("id", $quotationId)->first();
        $pageTitle = 'Quotation: ' . $quotationsInfo->title;
        // dd($challanItems);
        // return $challanItems;
        return view('quotation.allChallanItems', compact('pageTitle', 'challanItems', 'quotationId', 'quotationsInfo'));
    }


    public function productUsedHistory(Request $request)
    {
        $pageTitle = 'Product Used History';
        $date = $request->date;
        $dateRange = request()->range;
        $quotation_id = request()->quotation_id;
        $challan_id = request()->challan_id;
        $product_id = request()->product_id;
        $floor_id = request()->floor_id;
        $query = ChallanItem::with('product', 'floor')->select('challan_items.*', 'quotations.quotation_number as quotation_number', 'challans.challan_number as challan_number', 'challans.challan_date as challan_date')
            ->leftJoin('challans', 'challans.id', '=', 'challan_items.challan_id')
            ->leftJoin('quotations', 'quotations.id', '=', 'challans.quotation_id')
            ->when($date, function ($query, $date) {
                return $query->whereDate('challans.challan_date', $date);
            })
            ->when($dateRange, function ($query, $dateRange) {
                $dateRange = explode(' to ', $dateRange);
                $givenDates = [
                    $dateRange[0] . ' 00:00:00',
                    $dateRange[1] . ' 23:59:59',
                ];
                return $query->whereBetween('challans.challan_date', [$dateRange[0], $dateRange[1]]);
            })
            ->when($quotation_id, function ($query, $quotation_id) {
                return $query->where('challans.quotation_id', $quotation_id);
            })
            ->when($challan_id, function ($query, $challan_id) {
                return $query->where('challans.id', $challan_id);
            })
            ->when($product_id, function ($query, $product_id) {
                return $query->where('challan_items.product_id', $product_id);
            })
            ->when($floor_id, function ($query, $floor_id) {
                return $query->where('challan_items.floor_id', $floor_id);
            })
            ->orderBy('challans.challan_date', 'desc');

        $challanItems = $query->paginate(gs()->pagination);

        $quotations = Quotation::whereStatus(1)->latest()->get();
        $challans = Challan::whereStatus(1)->latest()->get();
        $products = Product::get();
        $floors = FloorInfo::get();
        // dd($products);
        // dd($challanItems);
        return view("quotation-reports.used-history", compact('pageTitle', 'challanItems', 'quotations', 'challans', 'products', 'floors'));
    }
}
