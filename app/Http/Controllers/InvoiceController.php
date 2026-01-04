<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\Quotation;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\PurchaseBatch;
use App\Models\ManageStock;
use App\Models\ManageStockItem;
use App\Models\Activitylog;


use App\Models\QuotationItem;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\approval_items;
use App\Models\PaymentItem;
use App\Models\PaymentRecord;
use Illuminate\Support\Facades\Hash;

class InvoiceController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:stock-list', ['only' => ['todayStock', 'monthStock', 'stockItems', 'detail', 'manageStock', 'manageStockItems']]);
    }

    public function index($quotationId)
    {
        $quotationsInfo = Quotation::where("id", $quotationId)->first();
        $pageTitle = 'Invoice Of Quotation: ' . $quotationsInfo->quotation_number;
        // $invoices = Invoice::where("quotation_id", $quotationId)->paginate(gs()->pagination);
        $invoicesQuery = Invoice::where("quotation_id", $quotationId);
        $invoices = $invoicesQuery->paginate(gs()->pagination);
        $totalPaidAmount = (clone $invoicesQuery)->sum('paid_amount');
        $totalDueAmount = (clone $invoicesQuery)->sum('due_amount');
        return view("quotation.invoices", compact('pageTitle', 'invoices', 'quotationId', 'quotationsInfo', 'totalPaidAmount', 'totalDueAmount'));
    }

    public function manageInvoiceCreate($quatationId)
    {
        $quotationsInfo = Quotation::where("id", $quatationId)->first();
        $pageTitle = 'Create Invoice fo Quotation: ' . $quotationsInfo->title;
        $lastId = Invoice::max('id');
        $invoiceNumber = $this->generateInvoiceNumber($lastId);
        $quotationItems = QuotationItem::with(['product'])
            ->where('quotation_id', $quatationId)
            ->get()
            ->each(function ($item) use ($quatationId) {
                // Manual query for approval items with floors
                $approvalItems = approval_items::with('floor.building')
                    ->where('product_id', $item->product_id)
                    ->whereHas('approval', function ($query) use ($quatationId) {
                        $query->where('quotation_id', $quatationId);
                    })
                    ->get();

                $floors = $approvalItems->pluck('floor')
                    ->unique()
                    ->values();

                $floors->transform(function ($floor) {
                    $buildingName = $floor->building ? $floor->building->name : 'N/A';
                    $floor->name = "{$buildingName} | {$floor->name}";
                    return $floor;
                });

                // Directly add floors as property to the model object
                $item->floors = $floors;
            });

        // dd($quotationItems->toArray());
        // dd($quotationItems[0]->approvals->toArray());

        return view('quotation.createInvoice', compact('pageTitle', 'invoiceNumber', 'quotationItems', 'quotationsInfo', 'quatationId'));
    }

    function generateInvoiceNumber($lastId)
    {
        $nextId = $lastId + 1;
        return "INV-" . str_pad($nextId, 5, "0", STR_PAD_LEFT);
    }
    function download($invoiceId)
    {
        $mpdf = setPdf();
        $invoice = Invoice::whereId($invoiceId)->first();
        $html = view('quotation.pdf', compact('invoice'))->render();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('invoice-' . $invoice->id . '.pdf', 'I'))->header('Content-Type', 'application/pdf');
    }

    public function manageInvoiceStore(Request $request)
    {
        // dd($request->all());
        // dd(json_encode($request->all()["floors"]["17"]));
        $request->validate([
            'invoice_number' => 'required|string',
            'quotation_id' => 'required|numeric',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable',
            // 'percentage' => 'nullable|numeric',
            'diagram_image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            // 'invoice_amount' => 'nullable|numeric|min:1',
            'notes' => 'nullable|string',
            'calculatedPrice' => 'numeric|min:1',
        ], [
            'calculatedPrice.min' => 'The invoice amount must be greater than or equal to 1 taka.'
        ]);

        DB::transaction(function () use ($request) {

            $input = $request->all();

            // dd($input);

            $invoice_number = $input['invoice_number'];
            $quotation_id = $input['quotation_id'];
            $invoice_date = $input['invoice_date'];
            $due_date = $input['due_date'] ?? null;
            // $percentage = $input['percentage'] ?? 0;
            $invoice_amount = $input['calculatedPrice'];
            $notes = $input['notes'];
            $vat = $input['vat'] ?? 0;

            // update quotation invoiced amount
            $quotation = Quotation::where('id', $quotation_id)->first();
            $quotation->invoiced_amount += $invoice_amount;
            $quotation->save();

            // create invoice
            $invoice = new Invoice();
            $invoice->quotation_id = $quotation_id;
            $invoice->invoice_number = $invoice_number;
            $invoice->invoice_date = $invoice_date;
            // $invoice->percentage = $percentage;
            $invoice->due_date = $due_date;
            if ($request->hasFile('diagram_image')) {
                $fileName = time() . '.' . $request->diagram_image->extension();
                $request->diagram_image->move(public_path('uploads/invoices'), $fileName);
                $invoice->diagram_image = $fileName;
            }
            $invoice->due_amount = $invoice_amount;
            $invoice->total_amount = $invoice_amount;
            $invoice->notes = $notes;
            $invoice->vat = $vat;
            $invoice->save();

            // insert invoiced qty in quotation items
            $this->insertInvoiceItems($input, $invoice->id, $quotation_id);
        });


        $message = 'Invoice Created Successfully.';
        $notify[] = ['success', $message];
        return to_route('invoice.all', $request->quotation_id)->withNotify($notify);
    }

    public function insertInvoiceItems($input, $invoiceId)
    {
        if (isset($input['products'])) {
            foreach ($input['products'] as $key => $product) {
                if ($input['qty'][$key] > 0) {
                    $invoiceItem = new InvoiceItems();
                    $invoiceItem->invoice_id = $invoiceId;
                    $invoiceItem->product_id = $product;
                    $invoiceItem->display_qty = $input['dummyDisplayQty'][$key];
                    $invoiceItem->percentage = $input['percentage'][$key];
                    $invoiceItem->quantity = $input['qty'][$key];
                    $invoiceItem->unit_price = $input['unitPrice'][$key];
                    $invoiceItem->total = $input['priceTotal'][$key];
                    $invoiceItem->due = $input['priceTotal'][$key];
                    $invoiceItem->description = $input['item_notes'][$key];
                    $invoiceItem->floors = isset($input['floors'][$product]) ? json_encode($input['floors'][$product]) : "[]";
                    $invoiceItem->save();

                    // update quotation items
                    $quotationItem = QuotationItem::where('quotation_id', $input['quotation_id'])->where('product_id', $product)->first();
                    $quotationItem->invoiced_qty = $quotationItem->invoiced_qty + $input['qty'][$key];
                    $quotationItem->save();
                }
            }
        }
    }


    public function manageInvoiceEdit($invoiceId)
    {
        $invoiceInfo = Invoice::find($invoiceId);
        $quotationsInfo = Quotation::where("id", $invoiceInfo->quotation_id)->first();
        $pageTitle = 'Update Invoice of Quotation : ' . $quotationsInfo->title . " and Invoice Number: " . $invoiceInfo->invoice_number;
        // $quotationItems = QuotationItem::with('product')->where("quotation_id", $invoiceInfo->quotation_id)->get();
        $quatationId = $invoiceInfo->quotation_id;
        $quotationItems = QuotationItem::with(['product'])
            ->where('quotation_id', $quatationId)
            ->get()
            ->each(function ($item) use ($quatationId) {
                // Manual query for approval items with floors
                $approvalItems = approval_items::with('floor.building')
                    ->where('product_id', $item->product_id)
                    ->whereHas('approval', function ($query) use ($quatationId) {
                        $query->where('quotation_id', $quatationId);
                    })
                    ->get();

                $floors = $approvalItems->pluck('floor')
                    ->unique()
                    ->values();

                $floors->transform(function ($floor) {
                    $buildingName = $floor->building ? $floor->building->name : 'N/A';
                    $floor->name = "{$buildingName} | {$floor->name}";
                    return $floor;
                });

                // Directly add floors as property to the model object
                $item->floors = $floors;
            });
        $invoiceItems = InvoiceItems::where("invoice_id", $invoiceId)->get();
        $totalProductPrice = InvoiceItems::where("invoice_id", $invoiceId)->sum('total');

        return view('quotation.createInvoice', compact('pageTitle', 'quotationsInfo', 'invoiceInfo', 'invoiceItems', 'quotationItems', 'totalProductPrice'));
    }


    public function manageInvoiceUpdate(Request $request, $invoiceId)
    {
        // dd($request->all());
        $request->validate([
            'invoice_number' => 'required|string',
            'quotation_id' => 'required|numeric',
            'invoice_date' => 'required|date',
            'diagram_image' => 'nullable|mimes:jpeg,png,jpg,svg|max:2048',
            // 'due_date' => 'nullable',
            // 'percentage' => 'nullable|numeric',
            'calculatedPrice' => 'nullable|numeric|min:1',
            'notes' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $invoiceId) {

            $input = $request->all();
            $invoiceInfo = Invoice::find($invoiceId);

            // dd($input);

            $invoice_number = $input['invoice_number'];
            $quotation_id = $input['quotation_id'];
            $invoice_date = $input['invoice_date'];
            $due_date = $input['due_date'] ?? null;
            // $percentage = $input['percentage'] ?? 0;
            $invoice_amount = $input['calculatedPrice'];
            $notes = $input['notes'];
            $vat = $input['vat'] ?? 0;

            // update quotation invoiced amount
            $quotation = Quotation::where('id', $quotation_id)->first();
            $quotation->invoiced_amount = ($quotation->invoiced_amount + $invoice_amount) - $invoiceInfo->total_amount;
            $quotation->save();

            // create invoice
            // $invoice = new Invoice();
            // $invoiceInfo->quotation_id = $quotation_id;
            $invoiceInfo->invoice_number = $invoice_number;
            $invoiceInfo->invoice_date = $invoice_date;
            // $invoiceInfo->percentage = $percentage;
            $invoiceInfo->due_date = $due_date;
            if ($request->hasFile('diagram_image')) {
                if ($invoiceInfo->diagram_image && file_exists(public_path('uploads/invoices/' . $invoiceInfo->diagram_image))) {
                    @unlink(public_path('uploads/invoices/' . $invoiceInfo->diagram_image));
                }
                $fileName = time() . '.' . $request->diagram_image->extension();
                $request->diagram_image->move(public_path('uploads/invoices'), $fileName);
                $invoiceInfo->diagram_image = $fileName;
            }

            $invoiceInfo->total_amount = $invoice_amount;
            $invoiceInfo->due_amount = $invoice_amount - $invoiceInfo->paid_amount;
            $invoiceInfo->notes = $notes;
            $invoiceInfo->vat = $vat;
            $invoiceInfo->save();

            $this->updateInvoiceItems($input, $invoiceInfo->id, $quotation_id);
            $this->deleteInvoiceItem($input, $invoiceId);
        });
        $activitylog = new Activitylog();
        $activitylog->user_id = auth()->user()->id ?? null;
        $activitylog->action_type = 'EDIT';
        $activitylog->table_name = 'invoices';
        $activitylog->record_id = $invoiceId ?? null;
        $activitylog->ip_address = session()->get('ip_address');
        $activitylog->remarks = 'invoices has been edited';
        $activitylog->timestamp = now();
        $activitylog->save();
        $message = 'Invoice Updated Successfully.';
        $notify[] = ['success', $message];
        return to_route('invoice.all', $request->quotation_id)->withNotify($notify);
    }

    public function deleteInvoiceItem($input, $invoiceId)
    {
        if (isset($input["deleltedProduct"])) {
            foreach ($input["deleltedProduct"] as $productIndex => $productId) {
                $invoiceItems = InvoiceItems::where("product_id", $productId)->where('invoice_id', $invoiceId)->first();
                // update quotation items
                $quotationItem = QuotationItem::where('quotation_id', $input['quotation_id'])->where('product_id', $productId)->first();
                $quotationItem->invoiced_qty = ($quotationItem->invoiced_qty  - $invoiceItems->quantity);
                $quotationItem->save();

                // perform delete action
                $invoiceItems->delete();
            }
        }
    }


    public function updateInvoiceItems($input, $invoiceId, $quotationId)
    {
        if (isset($input['products'])) {
            foreach ($input['products'] as $key => $product) {
                if ($input['qty'][$key] > 0) {
                    $invoiceItem = InvoiceItems::where('invoice_id', $invoiceId)->where('product_id', $product)->first();

                    if ($invoiceItem) {
                        // update quotation items
                        $quotationItem = QuotationItem::where('quotation_id', $quotationId)->where('product_id', $product)->first();
                        $quotationItem->invoiced_qty = ($quotationItem->invoiced_qty + $input['qty'][$key]) - $invoiceItem->quantity;
                        $quotationItem->save();

                        $invoiceItem->quantity = $input['qty'][$key];
                        $invoiceItem->unit_price = $input['unitPrice'][$key];
                        $diff = intval($input['priceTotal'][$key]) - intval($invoiceItem->total);
                        // dd($diff);
                        $invoiceItem->due = intval($invoiceItem->due) + intval($diff);
                        $invoiceItem->total = $input['priceTotal'][$key];
                        $invoiceItem->description = $input['item_notes'][$key];
                        // $invoiceItem->floors = json_encode($input['floors'][$product]);
                        $invoiceItem->floors = isset($input['floors'][$product]) ? json_encode($input['floors'][$product]) : "[]";
                        $invoiceItem->save();
                    } else {
                        // update quotation items
                        $quotationItem = QuotationItem::where('quotation_id', $quotationId)->where('product_id', $product)->first();
                        $quotationItem->invoiced_qty = ($quotationItem->invoiced_qty + $input['qty'][$key]);
                        $quotationItem->save();
                        // insert new invoice item
                        $invoiceItem = new InvoiceItems();
                        $invoiceItem->invoice_id = $invoiceId;
                        $invoiceItem->product_id = $product;
                        $invoiceItem->display_qty = $input['dummyDisplayQty'][$key];
                        $invoiceItem->percentage = $input['percentage'][$key];
                        $invoiceItem->quantity = $input['qty'][$key];
                        $invoiceItem->unit_price = $input['unitPrice'][$key];
                        $invoiceItem->total = $input['priceTotal'][$key];
                        $invoiceItem->due = $input['priceTotal'][$key];
                        $invoiceItem->description = $input['item_notes'][$key];
                        // $invoiceItem->floors = json_encode($input['floors'][$product]);
                        $invoiceItem->floors = isset($input['floors'][$product]) ? json_encode($input['floors'][$product]) : "[]";;
                        $invoiceItem->save();
                    }
                }

                // update quotation items
                $quotationItem = QuotationItem::where('quotation_id', $quotationId)->where('product_id', $product)->first();
                $quotationItem->invoiced_qty = ($quotationItem->invoiced_qty + $input['qty'][$key]) - $invoiceItem->quantity;
                $quotationItem->save();
            }
        }
    }


    public function deleteInvoice(Request $request,$invoiceId)
    {
        if(!Hash::check($request->password, auth()->user()->password)){
            $notify[] = ['error', 'Incorrect password. Deletion cancelled.'];
            return back()->withNotify($notify);
        }
        
        $invoiceInfo = Invoice::find($invoiceId);
        $quotation = Quotation::where('id', $invoiceInfo->quotation_id)->first();
        DB::transaction(function () use ($invoiceId, $invoiceInfo, $quotation) {
            // update quotation invoiced amount
            $quotation->invoiced_amount = $quotation->invoiced_amount - $invoiceInfo->total_amount;
            $quotation->save();


            // delete invoice items and update quotation items
            $invoiceItems = InvoiceItems::where("invoice_id", $invoiceId)->get();
            foreach ($invoiceItems as $itemKey => $item) {
                // update quotation items
                $quotationItem = QuotationItem::where('quotation_id', $quotation->id)->where('product_id', $item->product_id)->first();
                $quotationItem->invoiced_qty = ($quotationItem->invoiced_qty  - $item->quantity);
                $quotationItem->save();

                $item->delete();
            }

            // delete payment records and payment items
            $paymentRecords = PaymentRecord::where('invoice_id', $invoiceId)->get();
            foreach ($paymentRecords as $recordKey => $record) {
                $paymentItems = PaymentItem::where('payment_id', $record->id)->get();
                foreach ($paymentItems as $itemKey => $item) {
                    $item->delete();
                }
                $record->delete();
            }
            // perform delete action
            $invoiceInfo->delete();
        });
        $activitylog = new Activitylog();
        $activitylog->user_id = auth()->user()->id ?? null;
        $activitylog->action_type = 'DELETE';
        $activitylog->table_name = 'invoices';
        $activitylog->record_id = $invoiceId ?? null;
        $activitylog->ip_address = session()->get('ip_address');
        $activitylog->remarks = 'invoices has been deleted';
        $activitylog->timestamp = now();
        $activitylog->save();
        $message = 'Invoice deleted successfylly.';
        $notify[] = ['success', $message];
        return to_route('invoice.all', $quotation->id)->withNotify($notify);
    }


    public function viewInvoiceInfo($invoiceId)
    {
        $invoiceInfo = Invoice::find($invoiceId);
        $quotationsInfo = Quotation::where("id", $invoiceInfo->quotation_id)->first();
        $pageTitle = 'Invoice of Quotation : ' . $quotationsInfo->title . " and Invoice Number: " . $invoiceInfo->invoice_number;
        $invoiceItems = InvoiceItems::with("product")->where("invoice_id", $invoiceId)->get();
        $totalProductPrice = InvoiceItems::where("invoice_id", $invoiceId)->sum('total');

        return view('quotation.invoice-view', compact('pageTitle', 'quotationsInfo', 'invoiceInfo', 'invoiceItems', 'totalProductPrice'));
    }


    public function itemsInfoView($invoiceId, $productId)
    {
        // individual payment records for invoice items
        $paymentItems = PaymentItem::with("product", "floor")->select('payment_items.*')
            ->leftJoin('payment_records', 'payment_records.id', '=', 'payment_items.payment_id')
            ->where('payment_items.product_id', $productId)
            ->where('payment_records.invoice_id', $invoiceId)
            ->get();

        $pageTitle = 'Payment Details';
        $backRoute = route('invoice.view', $invoiceId);


        return view('quotation.payment-details', compact('paymentItems', 'pageTitle', 'backRoute'));
    }


    public function getInvoicedItems($quotationId, $productId)
    {
        $invoiceItems = InvoiceItems::with("product")->select('invoice_items.*', 'invoices.quotation_id')
            ->leftJoin('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->where('invoices.quotation_id', $quotationId)
            ->where('invoice_items.product_id', $productId)
            ->paginate(gs()->pagination);
        $quotationsInfo = Quotation::where("id", $quotationId)->first();
        $pageTitle = 'Quotation: ' . $quotationsInfo->title;
        // dd($invoiceItems);
        // return $invoiceItems;
        return view('quotation.allInvoicedItems', compact('pageTitle', 'invoiceItems', 'quotationId', 'quotationsInfo'));
    }
}
