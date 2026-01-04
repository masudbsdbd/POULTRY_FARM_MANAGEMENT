<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Investor;
use App\Models\Bank;

class InvestorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:investor-list', ['only' => ['index']]);
        $this->middleware('permission:investor-create|investor-edit', ['only' => ['store']]);
        $this->middleware('permission:investor-create', ['only' => ['create']]);
        $this->middleware('permission:investor-edit', ['only' => ['edit']]);
        $this->middleware('permission:investor-delete', ['only' => ['delete']]);
    }
    //
    public function index()
    {
        $pageTitle = "All Investors";
        $investors = Investor::latest()->notDeleted()->get();
        
        return view('investor.index',compact('pageTitle','investors'));
    }

    public function create()
    {
        $pageTitle = "Investor Create";
        // $banks = Bank::whereStatus(1)->notDeleted()->latest()->get();
        // return view('investor.create', compact('pageTitle','banks'));

        return view('investor.create', compact('pageTitle'));
    }
    
    public function edit($id)
    {
        $pageTitle = 'Edit Investor';
        $investors = Investor::find($id);
        return view('investor.create', compact('investors', 'pageTitle'));
    }

    public function store(Request $request, $id = 0)
    {
        // dd($request->all());
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'mobile'            => 'required|numeric',
            'address'           => 'required|string|max:255',
        ]);

        if ($id > 0) {
            $investor = Investor::whereId($id)->first();
            $investor->update_by = auth()->user()->id;
            $message = 'Investor updated successfully';
        } else {
            $investor = new Investor();
            $message = 'Investor has been created successfully';
        }

        $investor->name              = $request->name;
        $investor->email             = $request->email;
        $investor->mobile            = $request->mobile;
        $investor->address           = $request->address;
        $investor->entry_by          = auth()->user()->id;
        $investor->save();

        $notify[] = ['success', "New Investor has been created successfully."];
        return to_route('investor.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $investor = Investor::find($id);

        $investor->is_deleted = 1;
        $investor->save();

        $notify[] = ['success', 'Investor has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
