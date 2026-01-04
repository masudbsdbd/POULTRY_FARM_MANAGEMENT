<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReceivableHead;
use Illuminate\Validation\Rule;

class ReceivableHeadController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('permission:balance-sheet-maintain', ['only' => ['index', 'store']]);
    }
    public function index()
    {
        $pageTitle = 'Accounts Receivable Head';
        $heads = ReceivableHead::latest()->paginate(gs()->pagination);
        return view('setting.receivable-head', compact('pageTitle', 'heads'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('receivable_heads', 'name')->ignore($id),
            ],
        ]);

        if ($id > 0) {
            $head = ReceivableHead::whereId($request->id)->first();
            $message = 'Receivable Head updated successfully';
        } else {
            $head = new ReceivableHead();
            $message = 'Receivable Head created successfully';
        }

        $head->name = $request->name;
        $head->description = $request->description;
        $head->type = 2;
        $head->save();

        $notify[] = ['success', $message];
        return to_route('receivable.index')->withNotify($notify);
    }
}
