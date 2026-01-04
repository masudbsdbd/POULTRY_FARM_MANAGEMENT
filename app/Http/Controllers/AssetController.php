<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Asset;
use App\Models\AssetHead;
use App\Models\BankTransaction;
use App\Models\Bank;

class AssetController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:asset-maintain', ['only' => ['create', 'headCreate', 'headindex', 'index', 'store', 'headEdit', 'headStore', 'headDelete']]);
    }

    public function create()
    {
        $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        $pageTitle = 'Create Assets';
        $heads = AssetHead::all();
        return view('accounts-asset.create', compact('pageTitle', 'heads', 'banks'));
    }

    public function headCreate()
    {
        $pageTitle = 'Create Asset Heads';

        return view('accounts-asset.head-create', compact('pageTitle'));
    }

    public function headindex(Request $request)
    {
        $pageTitle = 'All Asset Heads';
    
        $assetHeads = AssetHead::query()->notDeleted()->paginate(gs()->pagination);
    
        return view('accounts-asset.head-index', compact('pageTitle', 'assetHeads'));
    }

    public function index(Request $request)
    {
        $pageTitle = 'All Assets';

        $todayTime = Carbon::now()->format('d-m-Y');
        $type = $request->type;
        $date = $request->date;
        $range = $request->range;
        $head = $request->head;


        $assetsQuery = Asset::query()->orderBy('id', 'desc');
        // $assets = Asset::with('assetHead')->orderBy('id', 'desc')->get();

        if ($head) {
            $assetsQuery->where('asset_head_id', $head);
        }


        if ($type) {
            // dd($type);
            if ($type == 1 && $date) {
                $givenDate = $date;
                $assetsQuery->whereDate('purchase_date', $givenDate);
            } elseif ($type == 2 && $range) {
                $dates = explode(' to ', $range);
                if (count($dates) == 2) {
                    $startDate = $dates[0] . ' 00:00:00';
                    $endDate = $dates[1] . ' 23:59:59';
                    $assetsQuery->whereBetween('purchase_date', [$startDate, $endDate]);
                }
                $givenDate = [$startDate, $endDate];
            } else {
                $notify[] = ['error', 'Kindly select a valid date or date range.'];
                return back()->withNotify($notify);
            }
        }

        $heads = AssetHead::all();
        $assets = $assetsQuery->paginate(gs()->pagination);

        // dd($assetsQuery);

        return view('accounts-asset.index', compact('pageTitle', 'assets', 'heads'));
    }

    public function store(Request $request, $id = 0)
    {

        // dd($request->all());
        $request->validate([
            'purchase_date' => 'required',
            'asset_head_id' => 'required',
            'name' => 'required|string|max:255',
            'purchase_price' => 'required',
            'description' => 'required',
        ]);

        $asset = new Asset();
        $asset->purchase_date = $request->purchase_date;
        $asset->asset_head_id = $request->asset_head_id;
        $asset->purchase_price = $request->purchase_price;
        $asset->name = $request->name;
        $asset->description = $request->description;
        $asset->entry_by = auth()->user()->id;
        $asset->save();

        $accArr = [
            'asset_id'         => $asset->id,
            'type'             => 18,
            'debit'            => $asset->purchase_price,
            'description'      => "Pay amount of " . ($asset->purchase_price ?? 0) . " Tk has been successfully paid as Asset.",
            'payment_method'   => $request->payment_method,
        ];


        $account = updateAcc($accArr, 'asset_id', $id, 18);

        if ($request->payment_method == 2) {

            $bankTrArr = [
                'account_id'       => $account->id,
                'withdrawer_name'  => $request->withdrawer_name,
                'debit'            => $request->purchase_price,
                'description'      => 'Company pay for Asset. Total amount ' . ($asset->purchase_price ?? 0) . ' Tk for purchase.',
                'bank_id'          => $request->bank_id,
                'check_no'         => $request->check_no,
            ];

            bankTr($account, $bankTrArr);
        } else {
            $transactionExist = BankTransaction::whereAccountId($account->id)->first();
            if (isset($transactionExist)) {
                $bank = Bank::whereId($transactionExist->bank_id)->first();
                $bank->balance += $transactionExist->debit;
                $bank->save();

                $transactionExist->delete();
            }
        }


        $message = 'Asset Created successfully';

        $notify[] = ['success', $message];
        return to_route('asset.index')->withNotify($notify);
    }


    public function headEdit($id)
    {
        // dd($id);
        $pageTitle = 'Edit Asset Head';
        $assetHead = AssetHead::find($id);
        return view('accounts-asset.head-create', compact('pageTitle', 'assetHead'));
    }

    public function headStore(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required',
        ]);
    
        if ($id > 0) {
            $asset = AssetHead::findOrFail($id);
            $message = 'Asset head has been updated successfully';
        } else {
            $asset = new AssetHead();
            $message = 'Asset head has been created successfully';
        }
    
        $asset->name = $request->name;
        $asset->description = $request->description;
        $asset->type = 1;
        $asset->save();
    
        $notify[] = ['success', $message];
        return to_route('asset.head.index')->withNotify($notify);
    }
    public function headDelete($id)
    {
            $file = AssetHead::find($id);
            $file->is_deleted = 1;
            $file->save();
    
            $notify[] = ['success', 'Asset Head has been successfully deleted'];
            return back()->withNotify($notify);
    }


}
