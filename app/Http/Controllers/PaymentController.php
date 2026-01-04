<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\PaymentRecord;
use App\Models\PaymentItem;
use App\Models\Customer;
use App\Models\FloorInfo;
use Illuminate\Support\Facades\Hash;
use App\Models\Activitylog;

class PaymentController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:stock-list', ['only' => ['todayStock', 'monthStock', 'stockItems', 'detail', 'manageStock', 'manageStockItems']]);
    }

    public function index($invoiceId)
    {
        $InvoiceInfo = Invoice::where("id", $invoiceId)->first();
        $pageTitle = 'Payment Records of Invoice: ' . $InvoiceInfo->invoice_number;
        $payments = PaymentRecord::where("invoice_id", $invoiceId)->paginate(gs()->pagination);
        $totalPaid = PaymentRecord::where("invoice_id", $invoiceId)->sum("amount");
        return view("quotation.payments", compact('pageTitle', 'payments', 'invoiceId', 'InvoiceInfo', 'totalPaid'));
    }


    public function managePaymentCreate($invoiceId)
    {
        $invoiceInfo = Invoice::where("id", $invoiceId)->first();
        $pageTitle = 'Create Payment Records of Invoice: ' . $invoiceInfo->invoice_number;
        $totalPaid = PaymentRecord::where("invoice_id", $invoiceId)->sum("amount");
        $invoiceItems = InvoiceItems::where("invoice_id", $invoiceId)->get();
        $floors = FloorInfo::get();
        return view('quotation.createPayment', compact('pageTitle', 'invoiceInfo', 'totalPaid', 'invoiceItems', 'floors'));
    }

    public function managePaymentStore(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'invoice_id' => 'required|numeric',
            'payment_date' => 'required|date',
            'calculatedPrice' => 'required|numeric',
            'payment_method' => 'required|numeric',
            'reference_no' => 'nullable|string',
            'notes' => 'nullable|string',
            // 'floors' => 'array|nullable',
        ], [
            'amount.min' => 'The amount must be greater than or equal to 1 taka.'
        ]);

        $input = $request->all();

        // dd($input);

        DB::transaction(function () use ($input) {
            $invoice_id = $input['invoice_id'];
            $payment_date = $input['payment_date'];
            $amount = $input['calculatedPrice'];
            $payment_method = $input['payment_method'];
            $reference_no = $input['reference_no'];
            $notes = $input['notes'];

            // update invoice paid & due amount
            $invoiceInfo = Invoice::where("id", $invoice_id)->first();
            $invoiceInfo->due_amount = $invoiceInfo->due_amount - $amount;
            $invoiceInfo->paid_amount = $invoiceInfo->paid_amount + $amount;
            // 'unpaid','partially_paid','paid','cancelled'
            if ($invoiceInfo->paid_amount == $invoiceInfo->total_amount) {
                $invoiceInfo->status = "paid";
            } else if ($invoiceInfo->paid_amount > 0 && $invoiceInfo->paid_amount < $invoiceInfo->total_amount) {
                $invoiceInfo->status = "partially_paid";
            } else {
                $invoiceInfo->status = "unpaid";
            }
            $invoiceInfo->save();

            // create payment record
            $payment = new PaymentRecord();
            $payment->invoice_id = $invoice_id;
            $payment->payment_date = $payment_date;
            $payment->amount = $amount;
            $payment->payment_method = $payment_method;
            $payment->reference_no = $reference_no;
            $payment->notes = $notes;
            $payment->save();

            $this->insertPaymentItems($input, $payment->id);
        });

        $message = 'Payment Added Successfully.';
        $notify[] = ['success', $message];
        return to_route('payment.all', $input['invoice_id'])->withNotify($notify);
    }


    public function insertPaymentItems($input, $paymentId)
    {
        if (isset($input['products'])) {
            foreach ($input['products'] as $key => $product) {
                if ($input['qty'][$key] > 0) {
                    $paymentItem = new PaymentItem();
                    $paymentItem->payment_id = $paymentId;
                    $paymentItem->product_id = $product;
                    // $paymentItem->floor_id = isset($input['floors'][$key]) ? $input['floors'][$key] : null;
                    $paymentItem->quantity = $input['qty'][$key];
                    $paymentItem->unit_price = $input['unitPrice'][$key];
                    $paymentItem->total = $input['priceTotal'][$key];
                    $paymentItem->save();

                    // update quotation items
                    $invoiceItem = InvoiceItems::where('invoice_id', $input['invoice_id'])->where('product_id', $product)->first();
                    $invoiceItem->paid_qty = $invoiceItem->paid_qty + $input['qty'][$key];
                    $invoiceItem->due = $invoiceItem->due - $input['priceTotal'][$key];
                    $invoiceItem->paid = $invoiceItem->paid + $input['priceTotal'][$key];
                    $invoiceItem->save();
                }
            }
        }
    }


    public function deletePayment(Request $request,$paymentId)
    {
        if(!Hash::check($request->password, auth()->user()->password)){
            $notify[] = ['error', 'Incorrect password. Deletion cancelled.'];
            return back()->withNotify($notify);
        }
        $paymentInfo = PaymentRecord::find($paymentId);
        $invoiceInfo = Invoice::where("id", $paymentInfo->invoice_id)->first();
        DB::transaction(function () use ($paymentInfo, $invoiceInfo) {
            // update invoice paid & due amount
            $invoiceInfo->due_amount = $invoiceInfo->due_amount + $paymentInfo->amount;
            $invoiceInfo->paid_amount = $invoiceInfo->paid_amount - $paymentInfo->amount;
            // 'unpaid','partially_paid','paid','cancelled'
            if ($invoiceInfo->paid_amount == $invoiceInfo->total_amount) {
                $invoiceInfo->status = "paid";
            } else if ($invoiceInfo->paid_amount > 0 && $invoiceInfo->paid_amount < $invoiceInfo->total_amount) {
                $invoiceInfo->status = "partially_paid";
            } else {
                $invoiceInfo->status = "unpaid";
            }
            $invoiceInfo->save();


            // delete payment items and update invoice items
            $paymentItems = PaymentItem::where("payment_id", $paymentInfo->id)->get();
            foreach ($paymentItems as $itemKey => $item) {
                // dd($item);
                // update quotation items
                $invoiceItem = InvoiceItems::where('invoice_id', $invoiceInfo->id)->where('product_id', $item->product_id)->first();
                $invoiceItem->paid_qty = ($invoiceItem->paid_qty  - $item->quantity);
                $invoiceItem->due = ($invoiceItem->due  + $item->total);
                $invoiceItem->paid = ($invoiceItem->paid  - $item->total);
                $invoiceItem->save();

                $item->delete();
            }



            // perform delete action
            $paymentInfo->delete();
        });
        $activitylog = new Activitylog();
        $activitylog->user_id = auth()->user()->id ?? null;
        $activitylog->action_type = 'DELETE';
        $activitylog->table_name = 'payment_records';
        $activitylog->record_id = $paymentId ?? null;
        $activitylog->ip_address = session()->get('ip_address');
        $activitylog->remarks = 'Payment has been deleted';
        $activitylog->timestamp = now();
        $activitylog->save();
        $message = 'Payment Deleted Successfully.';
        $notify[] = ['success', $message];
        return to_route('payment.all', $invoiceInfo->id)->withNotify($notify);
    }

    public function getPaymentItems(Request $request, $paymentId, $invoiceId)
    {
        $paymentItems = PaymentItem::with("product")->select('payment_items.*')
            ->leftJoin('payment_records', 'payment_records.id', '=', 'payment_items.payment_id')
            ->where('payment_records.id', $paymentId)
            ->get();
        // dd($paymentItems);

        $pageTitle = 'Payment Details';

        $backRoute = route('payment.all', $invoiceId);

        return view('quotation.payment-details', compact('paymentItems', 'pageTitle', 'backRoute'));
    }

    public function paymentHistoryDetails($paymentId)
    {
        $paymentItems = PaymentItem::with("product")->select('payment_items.*')
            ->leftJoin('payment_records', 'payment_records.id', '=', 'payment_items.payment_id')
            ->where('payment_records.id', $paymentId)
            ->get();
        // dd($paymentItems);

        $pageTitle = 'Payment Details';

        $backRoute = route('payment.history');

        return view('quotation.payment-details', compact('paymentItems', 'pageTitle', 'backRoute'));
    }


    public function paymentHistory(Request $request)
    {

        $pageTitle = 'Payment History';
        $date = $request->date;
        $dateRange = request()->range;
        $customers = Customer::whereStatus(1)->latest()->get();

        $query = PaymentRecord::select('payment_records.*', 'quotations.quotation_number as quotation_number', 'invoices.invoice_number as invoice_number')
            ->leftJoin('invoices', 'invoices.id', '=', 'payment_records.invoice_id')
            ->leftJoin('quotations', 'quotations.id', '=', 'invoices.quotation_id')
            ->when($date, function ($query, $date) {
                return $query->whereDate('payment_records.payment_date', $date);
            })
            ->when($dateRange, function ($query, $dateRange) {
                $dateRange = explode(' to ', $dateRange);
                $givenDates = [
                    $dateRange[0] . ' 00:00:00',
                    $dateRange[1] . ' 23:59:59',
                ];
                return $query->whereBetween('payment_records.payment_date', [$dateRange[0], $dateRange[1]]);
            })
            ->when(request('customer_id'), function ($query, $customerId) {
                return $query->where('quotations.customer_id', $customerId);
            })
            ->when(request('payment_method'), function ($query, $payment_method) {
                return $query->where('payment_records.payment_method', $payment_method);
            });
        $payments = $query->paginate(gs()->pagination);

        $payments = $query->paginate(gs()->pagination);

        $totalSum   = (clone $query)->sum('payment_records.amount');
        $totalCash  = (clone $query)->where('payment_records.payment_method', '1')->sum('payment_records.amount');
        $totalBank  = (clone $query)->where('payment_records.payment_method', '2')->sum('payment_records.amount');
        $totalCheque = (clone $query)->where('payment_records.payment_method', '3')->sum('payment_records.amount');


        $totalPaid = PaymentRecord::sum("amount");
        return view("quotation-reports.payments-history", compact('pageTitle', 'payments', 'totalPaid', 'customers', 'totalSum', 'totalCash', 'totalBank', 'totalCheque'));
    }
}
