<?php

namespace App\Http\Controllers;

use App\Models\PayableHead;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PayableHeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:balance-sheet-maintain', ['only' => ['index', 'store']]);
    }
    public function index()
    {
        $pageTitle = 'Accounts Payable Head';
        $heads = PayableHead::latest()->paginate(gs()->pagination);
        return view('setting.payable-head', compact('pageTitle', 'heads'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('payable_heads', 'name')->ignore($id),
            ],
        ]);

        if ($id > 0) {
            $head = PayableHead::whereId($request->id)->first();
            $message = 'Payable Head updated successfully';
        } else {
            $head = new PayableHead();
            $message = 'Payable Head created successfully';
        }

        $head->name = $request->name;
        $head->description = $request->description;
        $head->type = 2;
        $head->save();

        $notify[] = ['success', $message];
        return to_route('payable.index')->withNotify($notify);
    }
}
