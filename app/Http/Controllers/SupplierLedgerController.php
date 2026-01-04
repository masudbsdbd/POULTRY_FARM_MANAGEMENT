<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Account;
use App\Models\Supplier;
use Carbon\Carbon;

class SupplierLedgerController extends Controller
{
    public function purchaseIndex(Request $request, $id)
    {
        $pageTitle = " Purchase History";
        $todayTime = Carbon::now()->format('d-m-Y');

        $type = $request->type;
        $date = $request->date;
        $range = $request->range;

        if ($range) {
            $dates = explode(' to ', $range);
            $givenDates = [
                $dates[0] . ' 00:00:00',
                $dates[1] . ' 23:59:59',
            ];
        }

        $purchases = Purchase::query();

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $purchases = $purchases->$clause('created_at', $givenDate);
        }

        $purchases = $purchases->with(['supplier', 'batch'])
            ->where('supplier_id', $id)
            ->latest()
            ->notDeleted()
            ->paginate(gs()->pagination);

        $supplierData = Supplier::find($id);

        if ($request->action == 'print') {
            $mpdf = setPdf();

            $data = isset($givenDate) ? compact('purchases', 'supplierData', 'givenDate') : compact('purchases', 'supplierData');
            $html = view('supplier-ledger.ledger-pdf', $data)->render();

            $mpdf->WriteHTML($html);
            return response($mpdf->Output('supplier-ledger-' . $id . '.pdf', 'I'))->header('Content-Type', 'application/pdf');
        }

        return view('supplier-ledger.purchase-history-index', compact('pageTitle', 'purchases', 'id', 'supplierData'));
    }

    public function accountIndex(Request $request, $id)
    {
        $pageTitle = " Account Ledger";
        $todayTime = Carbon::now()->format('d-m-Y');

        $type = $request->type;
        $date = $request->date;
        $range = $request->range;

        if ($range) {
            $dates = explode(' to ', $range);
            $givenDates = [
                $dates[0] . ' 00:00:00',
                $dates[1] . ' 23:59:59',
            ];
        }

        $supplierAccData = Account::query();

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $supplierAccData = $supplierAccData->$clause('created_at', $givenDate);
        }

        $supplierAccData = $supplierAccData->where('supplier_id', $id)
            ->with('purchase')
            ->get();

        $supplierData = Supplier::find($id);

        if ($request->action == 'print') {
            $mpdf = setPdf();

            $data = isset($givenDate) ? compact('supplierAccData', 'supplierData', 'givenDate') : compact('supplierAccData', 'supplierData');
            $html = view('supplier-ledger.account-pdf', $data)->render();

            $mpdf->WriteHTML($html);
            return response($mpdf->Output('supplier-ledger-' . $id . '.pdf', 'I'))->header('Content-Type', 'application/pdf');
        }

        return view('supplier-ledger.account-ledger-index', compact('pageTitle', 'supplierAccData', 'id', 'supplierData'));
    }
}
