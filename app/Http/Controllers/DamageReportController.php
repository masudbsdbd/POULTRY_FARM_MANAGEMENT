<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
// use App\Models\Damage;
use App\Models\ManageStock;
use App\Models\Supplier;

class DamageReportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:damage-report-list', ['only' => ['index']]);
    }
    public function index(Request $request)
    {
        if ($request->type == 2 && $request->date == null) {
            $notify[] = ['error', 'Please select the correct date and range.'];
            return back()->withNotify($notify);
        }

        // dd($request->all());
        $pageTitle = 'Damage Report';
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
        $supplier_id = $request->supplier_id;
        // dd($supplier_id);

        $damages = ManageStock::where('stock_status', 1);

        if ($type) {
            $givenDate = $type == 1 ? $date : $givenDates;
            if (!isset($givenDate)) {
                $notify[] = ['error', 'Kindly select date.'];
                return back()->withNotify($notify);
            }
            $clause = $type == 1 ? 'whereDate' : 'whereBetween';
            $damages = $damages->$clause('created_at', $givenDate);
            // dd($givenDate);
        }
        if ($supplier_id) {
            $damages = $damages->where('supplier_id', $supplier_id);
        }

        $damages = $damages->with(['supplier', 'items.product', 'items.batch'])
        ->latest()
        ->paginate(gs()->pagination);



        // dd($damages->toArray());

        $suppliers = Supplier::latest()->notDeleted()->get();


        return view('damage-report.index', compact('pageTitle', 'todayTime', 'damages', 'suppliers'));
    }
}
