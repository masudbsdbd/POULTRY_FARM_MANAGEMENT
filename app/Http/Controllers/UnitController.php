<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:unit-list', ['only' => ['index']]);
        $this->middleware('permission:unit-create|unit-edit', ['only' => ['store']]);
        $this->middleware('permission:unit-create', ['only' => ['create']]);
        $this->middleware('permission:unit-edit', ['only' => ['edit']]);
        $this->middleware('permission:unit-delete', ['only' => ['delete']]);
    }
    public function index()
    {
        $pageTitle = 'Unit List';
        $units = Unit::latest()->notDeleted()->paginate(gs()->pagination);
        return view('unit.index', compact('pageTitle', 'units'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('units', 'name')->ignore($id),
            ],
        ]);

        if ($id > 0) {
            $unit = Unit::whereId($request->id)->first();
            $message = 'Unit has been updated successfully';
            $givenStatus = isset($request->editcatstatus) ? 1 : 0;
        } else {
            $unit = new Unit();
            $message = 'Unit has been created successfully';
            $givenStatus = isset($request->status) ? 1 : 0;
        }

        $unit->name = $request->name;
        $unit->status = $givenStatus;
        $unit->save();

        $notify[] = ['success', $message];
        return to_route('unit.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $unit = Unit::find($id);
        $unit->delete();
        // $unit->is_deleted = 1;
        // $unit->save();

        $notify[] = ['success', 'Unit has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
