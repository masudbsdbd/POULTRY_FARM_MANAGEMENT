<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\approval_items;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Unit;
use App\Models\Challan;
use App\Models\ChallanItem;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\PaymentRecord;
use App\Models\PaymentItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Activitylog;


class QuotationController extends Controller
{
    //


    public function index(Request $request)
    {
        // dd(request()->all());
        $date = $request->date;
        $dateRange = request()->range;
        $pageTitle = 'Quotation List';
        $quotations = Quotation::with('items.product', 'customer')
            ->when($date, function ($query, $date) {
                return $query->where('quotation_date', $date);
            })
            ->when($dateRange, function ($query, $dateRange) {
                $dateRange = explode(' to ', $dateRange);
                return $query->whereBetween('quotation_date', [$dateRange[0], $dateRange[1]]);
            })
            ->when(request('customer_id'), function ($query, $customerId) {
                return $query->where('customer_id', $customerId);
            })
            ->latest()
            ->paginate(gs()->pagination);


        // $query = Quotation::with('items.product', 'customer')
        //     ->when($date, fn($q) => $q->where('quotation_date', $date))
        //     ->when($dateRange, function ($q, $dateRange) {
        //         $dateRange = explode(' to ', $dateRange);
        //         return $q->whereBetween('quotation_date', [$dateRange[0], $dateRange[1]]);
        //     })
        //     ->when(request('customer_id'), fn($q, $customerId) => $q->where('customer_id', $customerId))
        //     ->leftJoin('invoices', 'quotations.id', '=', 'invoices.quotation_id')
        //     ->leftJoin('payment_records', 'invoices.id', '=', 'payment_records.invoice_id')
        //     ->leftJoin('payment_items', 'payment_records.id', '=', 'payment_items.payment_id')
        //     ->select(
        //         'quotations.id',
        //         'quotations.title',
        //         'quotations.quotation_number',
        //         'quotations.customer_id',
        //         'quotations.quotation_date',
        //         'quotations.expiry_date',
        //         'quotations.total_amount',
        //         'quotations.used_product_amount',
        //         'quotations.invoiced_amount',
        //         'quotations.notes',
        //         'quotations.status',
        //         'quotations.diagram_image',
        //         'quotations.project_name',
        //         'quotations.floor_info',
        //         'quotations.created_at',
        //         'quotations.updated_at'
        //     )
        //     ->selectRaw('COALESCE(SUM(payment_records.amount),0) as total_paid')
        //     ->selectRaw('COALESCE(SUM(invoices.due_amount),0) as total_due')
        //     ->groupBy(
        //         'quotations.id',
        //         'quotations.title',
        //         'quotations.quotation_number',
        //         'quotations.customer_id',
        //         'quotations.quotation_date',
        //         'quotations.expiry_date',
        //         'quotations.total_amount',
        //         'quotations.used_product_amount',
        //         'quotations.invoiced_amount',
        //         'quotations.notes',
        //         'quotations.status',
        //         'quotations.diagram_image',
        //         'quotations.project_name',
        //         'quotations.floor_info',
        //         'quotations.created_at',
        //         'quotations.updated_at'
        //     )
        //     ->latest('quotations.created_at');

        // $quotations = $query->paginate(gs()->pagination);

        $totals = Quotation::leftJoin('invoices', 'quotations.id', '=', 'invoices.quotation_id')
            ->leftJoin('payment_records', 'invoices.id', '=', 'payment_records.invoice_id')
            ->selectRaw('COALESCE(SUM(payment_records.amount),0) as total_paid')
            ->selectRaw('COALESCE(SUM(invoices.due_amount),0) as total_due')
            ->first();

        $totalQuotationPaidAmount = $totals->total_paid;
        $totalQuotationDueAmount  = $totals->total_due;

        $totalQuotations = Quotation::count();
        $totalQuotationAmount = Quotation::sum('total_amount');
        $totalInvoicedAmount = Quotation::sum('invoiced_amount');
        view()->share('totalQuotationAmount', $totalQuotationAmount);
        view()->share('totalQuotations', $totalQuotations);
        view()->share('totalInvoicedAmount', $totalInvoicedAmount);
        view()->share('totalQuotationPaidAmount', $totalQuotationPaidAmount);
        view()->share('totalQuotationDueAmount', $totalQuotationDueAmount);

        // $units = Unit::get();

        $customers = Customer::whereStatus(1)->latest()->get();
        view()->share('customers', $customers);

        return view('quotation.index', compact('quotations', 'pageTitle'));
    }

    public function create()
    {
        $pageTitle = 'Create Quotation';
        $customers = Customer::whereStatus(1)->latest()->get();
        $products = Product::with("unit")->latest()->get();
        $units = Unit::get();

        return view('quotation.create', compact('pageTitle', 'customers', 'products', 'units'));
    }



    public function store(Request $request, $id = 0)
    {
        // dd($request->all());
        $request->validate([
            'title'          => 'required|string|max:255',
            'customer_id'    => 'required|integer',
            'quotation_date' => 'required|date',
            'expiry_date'    => 'nullable|date',
            'notes'          => 'nullable|string|max:255',
            'diagram_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'products'       => 'required|array',
        ]);

        if ($id > 0) {
            $this->createNewProduct($request, $id);
            $this->deleteQuotationItem($request, $id);

            $quotation = Quotation::findOrFail($id);
            $message   = 'Quotation has been updated successfully';
            $quotation->title          = $request->title;
            $quotation->customer_id    = $request->customer_id;
            $quotation->project_name   = $request->project_name ?? null;
            $quotation->floor_info     = $request->floor_info;
            $quotation->quotation_date = $request->quotation_date;
            $quotation->expiry_date    = $request->expiry_date;
            $quotation->status         = $request->has('status') ? 1 : 0;
            $quotation->notes          = $request->notes;
            if ($request->hasFile('diagram_image')) {
                if ($quotation->diagram_image && file_exists(public_path('uploads/quotations/' . $quotation->diagram_image))) {
                    @unlink(public_path('uploads/quotations/' . $quotation->diagram_image));
                }

                $fileName = time() . '.' . $request->diagram_image->extension();
                $request->diagram_image->move(public_path('uploads/quotations'), $fileName);
                $quotation->diagram_image = $fileName;
            }
            $quotation->save();

            $plusTempTotal = 0;
            foreach ($request->products as $itemKey => $item) {
                // dd($request->qty[$itemKey]);
                $individualItem = QuotationItem::where('quotation_id', $quotation->id)->where('product_id', $item)->first();
                if ($individualItem) {
                    $individualItem->qty = ($individualItem->qty + $request->qty[$itemKey]) - $individualItem->qty;
                    if ($request->qty[$itemKey] < $individualItem->used_qty) {
                        $message = 'You can not reduce the quantity because some quantity has already been used in invoice.';

                        $notify[] = ['error', $message];
                        continue;
                    }
                    $totalNewAmount = intval(intval($request->qty[$itemKey]) * intval($individualItem->unit_price));
                    $plusTempTotal += ($totalNewAmount - intval($individualItem->total));
                    $individualItem->total = $totalNewAmount;
                    $individualItem->save();
                } else {
                    $totalNewAmount = intval(intval($request->qty[$itemKey]) * intval($request->unitPrice[$itemKey]));
                    $plusTempTotal += $totalNewAmount;
                    QuotationItem::create([
                        'quotation_id' => $quotation->id,
                        'product_id'  => $item,
                        'description' => $request->description[$itemKey] ?? null,
                        'qty'         => $request->qty[$itemKey],
                        'unit_price'  => $request->unitPrice[$itemKey],
                        'total'       => $totalNewAmount,
                    ]);
                }
            }

            // dd($plusTempTotal);
            $quotation->total_amount += $plusTempTotal;
            $quotation->save();
            $activitylog = new Activitylog();
            $activitylog->user_id = auth()->user()->id ?? null;
            $activitylog->action_type = 'EDIT';
            $activitylog->table_name = 'quotations';
            $activitylog->record_id = $id ?? null;
            $activitylog->ip_address = session()->get('ip_address');
            $activitylog->remarks = 'quotations has been edited';
            $activitylog->timestamp = now();
            $activitylog->save();
            if (isset($notify)) {
                return to_route('quotation.edit', $id)->withNotify($notify);
            }
        } else {
            $quotation = new Quotation();
            $message   = 'Quotation has been created successfully';

            $last   = Quotation::latest('id')->first();
            $number = $last ? intval(substr($last->quotation_number, 2)) + 1 : 1;
            $quotation->quotation_number = 'QN' . str_pad($number, 2, '0', STR_PAD_LEFT);

            $quotation->title          = $request->title;
            $quotation->customer_id    = $request->customer_id;
            $quotation->project_name   = $request->project_name ?? null;
            $quotation->floor_info     = $request->floor_info;
            $quotation->quotation_date = $request->quotation_date;
            $quotation->expiry_date    = $request->expiry_date;
            $quotation->status         = $request->has('status') ? 1 : 0;
            $quotation->notes          = $request->notes;
            $quotation->total_amount   = 0;
            if ($request->hasFile('diagram_image')) {
                $fileName = time() . '.' . $request->diagram_image->extension();
                $request->diagram_image->move(public_path('uploads/quotations'), $fileName);
                $quotation->diagram_image = $fileName;
            }
            $quotation->save();

            $products     = $request->products ?? [];
            $descriptions = $request->description ?? [];
            $qtys         = $request->qty ?? [];
            $prices       = $request->unitPrice ?? [];

            $totalAmount = 0;

            foreach ($products as $index => $productId) {
                $qty   = (int)($qtys[$index] ?? 0);
                $price = (float)($prices[$index] ?? 0);
                $desc  = $descriptions[$index] ?? null;
                $total = $qty * $price;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id'  => $productId,
                    'description' => $desc,
                    'qty'         => $qty,
                    'unit_price'  => $price,
                    'total'       => $total,
                ]);

                $totalAmount += $total;
            }

            $quotation->total_amount = $totalAmount;
            $quotation->save();

            // create new products
            $this->createNewProduct($request, $quotation->id);
        }

        return redirect()->route('quotation.index')->with('success', $message);
    }


    public function deleteQuotationItem($request, $quotationId)
    {
        $deletedProducts = $request->deleltedProduct ?? [];
        if (!empty($deletedProducts)) {
            foreach ($deletedProducts as $productId) {
                $item = QuotationItem::where('quotation_id', $quotationId)->where('product_id', $productId)->first();
                if ($item) {
                    if ($item->used_qty > 0) {
                        return back()->with('error', 'You can not delete some products because some quantity has already been used in invoice.');
                    }
                    $item->delete();
                    $quotation = Quotation::findOrFail($quotationId);
                    $quotation->total_amount -= $item->total;
                    $quotation->save();
                }
            }
        }
    }


    public function createNewProduct($request, $quotationId)
    {
        $products = $request->new_products_name ?? [];
        $description = $request->new_products_description ?? [];
        $units = $request->new_units ?? [];
        $notes = $request->new_notes ?? [];
        $qtys = $request->new_qty ?? [];
        $prices = $request->new_unitPrice ?? [];
        foreach ($products as $index => $productName) {
            $product = new Product();
            $product->name = $productName;
            $product->description = $description[$index] ?? null;
            $product->unit_id = $units[$index] ?? null;
            $product->is_deleted = 0;
            $product->price = $prices[$index] ?? 0;
            $product->status = 1;
            $product->save();

            // Now, add this product to the QuotationItem table
            $qty = (int)($qtys[$index] ?? 0);
            $price = (float)($prices[$index] ?? 0);
            $total = $qty * $price;
            QuotationItem::create([
                'quotation_id' => $quotationId,
                'product_id'  => $product->id,
                'description' => $notes[$index] ?? null,
                'qty'         => $qty,
                'unit_price'  => $price,
                'total'       => $total,
            ]);

            // Update the total amount in the Quotation table
            $quotation = Quotation::findOrFail($quotationId);
            $quotation->total_amount += $total;
            $quotation->save();
        }
    }


    public function edit($id)
    {
        $pageTitle = 'Edit Quotation';

        $quotation = Quotation::with('items')->findOrFail($id);
        $customers = Customer::whereStatus(1)->latest()->get();
        $products  = Product::with("unit")->latest()->get();
        // dd($products);
        $units = Unit::get();
        return view('quotation.create', compact('pageTitle', 'quotation', 'customers', 'products', 'units'));
    }


    public function QuotationView($quotationId)
    {
        $quotation = Quotation::with('items')->findOrFail($quotationId);
        $pageTitle = 'View Quotation Detail of #' . $quotation->quotation_number;
        $customers = Customer::whereStatus(1)->latest()->get();
        $products  = Product::latest()->get();


        // dd($quotation);

        return view('quotation.quotation-view', compact('pageTitle', 'quotation', 'customers', 'products'));
    }

    public function generatePdf($id)
    {
        $mpdf = setPdf();
        $quotation = Quotation::with('items')->findOrFail($id);
        $html = view('quotation.product-pdf', compact('quotation'))->render();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('invoice-' . $quotation->id . '.pdf', 'I'))->header('Content-Type', 'application/pdf');
    }


    public function delete(Request $request , $id)
    {
        if (!Hash::check($request->password, auth()->user()->password)) {
            $notify[] = ['error', 'Incorrect password. Deletion cancelled.'];
            return back()->withNotify($notify);
        }
        DB::transaction(function () use ($id) {
            $quotation = Quotation::findOrFail($id);
            $quotation->delete();

            // delete quotation items
            QuotationItem::where('quotation_id', $id)->delete();

            // delete challans & challan items
            Challan::where('quotation_id', $id)->get()->each(function ($challan) {
                ChallanItem::where('challan_id', $challan->id)->delete();
                $challan->delete();
            });


            // delete invoices & invoice items & payments
            Invoice::where('quotation_id', $id)->get()->each(function ($invoice) {
                InvoiceItems::where('invoice_id', $invoice->id)->delete();
                // PaymentRecord::where('invoice_id', $invoice->id)->delete();
                PaymentRecord::where('invoice_id', $invoice->id)->get()->each(function ($payment) {
                    // delete payment items
                    // PaymentItem::where('payment_id', $payment->id)->delete();
                    PaymentItem::where('payment_id', $payment->id)->get()->each(function ($item) {
                        $item->delete();
                    });

                    $payment->delete();
                });
                $invoice->delete();
            });


            // delete approvals & approval items
            Approval::where('quotation_id', $id)->get()->each(function ($approval) {
                approval_items::where('approval_id', $approval->id)->delete();
                $approval->delete();
            });
        });
            $activitylog = new Activitylog();
            $activitylog->user_id = auth()->user()->id ?? null;
            $activitylog->action_type = 'DELETE';
            $activitylog->table_name = 'quotations';
            $activitylog->record_id = $id ?? null;
            $activitylog->ip_address = session()->get('ip_address');
            $activitylog->remarks = 'quotations has been deleted';
            $activitylog->timestamp = now();
            $activitylog->save();
        $notify[] = ['success', 'Quotation has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
