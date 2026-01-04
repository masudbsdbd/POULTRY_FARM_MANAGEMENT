<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockItem;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:stock-list', ['only' => ['todayStock', 'monthStock', 'stockItems', 'detail']]);
    // }
    public function index()
    {
        $pageTitle = 'Warehouse';
        $users = User::select("id", "name")->get();

        $warehouses = Warehouse::select('warehouses.*', 'users.name as manager_name')
            ->leftJoin('users', 'users.id', '=', 'warehouses.warehouse_manager')->latest()->paginate(gs()->pagination);

        return view('warehouse.index', compact('pageTitle', 'users', 'warehouses'));
    }

    // public function manage(Request $request)
    // {
    //     // dd($request->all());
    //     $pageTitle = 'Manage Warehouse';
    //     $warehouses = WareHouse::with('stocks.batch','user')->get();
    //     // dd($warehouses->toArray());
    //     return view('warehouse.manage', compact('pageTitle', 'warehouses'));
    // }

    public function manage(Request $request)
    {
        $pageTitle = 'Manage Warehouse';
        // $todayTime = Carbon::now()->format('d-m-Y');

        // $type = $request->type;
        // $date = $request->date;
        // $range = $request->range;
        // $warehouse_id = $request->warehouse_id;

        // if ($range) {
        //     $dates = explode(' to ', $range);
        //     if (count($dates) == 2) {
        //         $givenDates = [
        //             $dates[0] . ' 00:00:00',
        //             $dates[1] . ' 23:59:59',
        //         ];
        //     } else {
        //         $notify[] = ['error', 'Invalid date range format. Please use "start date to end date".'];
        //         return back()->withNotify($notify);
        //     }
        // }

        $query = WareHouse::with('stocks.batch', 'user');

        // if ($type) {
        //     $givenDate = $type == 1 ? $date : ($givenDates ?? null);

        //     if (!$givenDate) {
        //         $notify[] = ['error', 'Kindly select a valid date.'];
        //         return back()->withNotify($notify);
        //     }

        //     $clause = $type == 1 ? 'whereDate' : 'whereBetween';
        //     $query->$clause('created_at', $givenDate);
        // }

        // if ($warehouse_id) {
        //     $query->where('id', $warehouse_id);
        // }

        $warehouses = $query->paginate(gs()->pagination);
        $warehouseNames = WareHouse::latest()->get();
        return view('warehouse.manage', compact('pageTitle', 'warehouses', 'warehouseNames'));
        // return view('warehouse.manage', compact('pageTitle', 'todayTime', 'warehouses', 'warehouseNames'));
    }


    public function store(Request $request)
    {
        $Warehouse = new Warehouse();
        $Warehouse->warehouse_code = $request->warehouse_code;
        $Warehouse->warehouse_name = $request->warehouse_name;
        $Warehouse->warehouse_address = $request->warehouse_address;
        $Warehouse->warehouse_manager = $request->warehouse_manager;
        $Warehouse->warehouse_phone = $request->warehouse_phone;
        $Warehouse->warehouse_email = $request->warehouse_email;
        // $Warehouse->warehouse_status = $request->warehouse_status;
        $Warehouse->save();

        $notify[] = ['success', 'Warehouse has been created successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $Warehouse = Warehouse::find($id);
        $Warehouse->warehouse_code = $request->warehouse_code;
        $Warehouse->warehouse_name = $request->warehouse_name;
        $Warehouse->warehouse_address = $request->warehouse_address;
        $Warehouse->warehouse_manager = $request->warehouse_manager;
        $Warehouse->warehouse_phone = $request->warehouse_phone;
        $Warehouse->warehouse_email = $request->warehouse_email;
        // $Warehouse->warehouse_status = $request->warehouse_status;
        $Warehouse->save();

        $notify[] = ['success', 'Warehouse has been updated successfully'];
        return back()->withNotify($notify);
    }


    public function destroy($id)
    {
        $Warehouse = Warehouse::find($id);
        $Warehouse->delete();

        $notify[] = ['success', 'Warehouse has been deleted successfully'];
        return back()->withNotify($notify);
    }

    public function manageCreate()
    {
        // dd('manageCreate');
        $pageTitle = 'Manage Warehouse Create';
        $warehouses = WareHouse::with('stocks.batch','user')->get();
        $stocks = Stock::with('product')->where('stock', '>', 0)->get();
        $uniqueStocks = $stocks->unique(function ($item) {
            return $item->product_id;
        });
        // dd($uniqueStocks->toArray());
        return view('warehouse.create', compact('pageTitle', 'uniqueStocks','stocks','warehouses'));
    }

    public function manageWarehouse($id)
    {
        $stocks = Stock::with('batch')->where('product_id', $id)->get();

        return response()->json($stocks->map(function ($stock) {
            return [
                'stock_id' => $stock->id,
                'batch_id' => $stock->batch->id,
                'batch_code' => $stock->batch->batch_code,
                'warehouse_id' => $stock->warehouse_id,
            ];
        }));
    }

    public function manageStore(Request $request)
    {
        $input = $request->all();

        $product_id = $input['product_id'];
        $batch_id = $input['batch_id'];
        $warehouse_id_to = $input['warehouse_id_to'];

        $manageWarehouse = Stock::where('product_id', $product_id)->where('purchase_batch_id', $batch_id)->first();
        if($manageWarehouse){
            $manageWarehouse->warehouse_id = $warehouse_id_to;  
            $manageWarehouse->save();
        }

        $message = 'Warehouse has been manaed successfully';
        $notify[] = ['success', $message];
        return to_route('warehouse.manage')->withNotify($notify);
        
    }


}
