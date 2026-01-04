<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Building;


class BuildingController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:unit-list', ['only' => ['index']]);
        // $this->middleware('permission:unit-create|unit-edit', ['only' => ['store']]);
        // $this->middleware('permission:unit-create', ['only' => ['create']]);
        // $this->middleware('permission:unit-edit', ['only' => ['edit']]);
        // $this->middleware('permission:unit-delete', ['only' => ['delete']]);
    }
    public function index()
    {
        $pageTitle = 'Building Info List';
        $buildings = Building::paginate(gs()->pagination);
        return view('building-info.index', compact('pageTitle', 'buildings'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name' => [
                'required',
                'string'
            ],
        ]);

        if ($id > 0) {
            $bilding = Building::whereId($request->id)->first();
            $message = 'Floor has been updated successfully';
        } else {
            $bilding = new Building();
            $message = 'floor has been created successfully';
        }

        $bilding->name = $request->name;
        $bilding->save();

        $notify[] = ['success', $message];
        return to_route('building.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $bilding = Building::find($id);
        $bilding->delete();
        // $bilding->is_deleted = 1;
        // $bilding->save();

        $notify[] = ['success', 'floor has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
