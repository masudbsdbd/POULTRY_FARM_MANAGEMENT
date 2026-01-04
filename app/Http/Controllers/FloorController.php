<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\FloorInfo;


class FloorController extends Controller
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
        $pageTitle = 'Floor Info List';
        $buildings = Building::all();
        $building_id = request()->has('building_id') ? request('building_id') : $buildings[0]->id;
        $floors = FloorInfo::with("building")
            ->when(isset($building_id), function ($query) use ($building_id) {
                $query->where('building_id', $building_id);
            })
            ->paginate(gs()->pagination);

        return view('floor-info.index', compact('pageTitle', 'floors', 'buildings', 'building_id'));
    }

    public function store(Request $request, $id = 0)
    {
        // dd($request->all());
        $request->validate([
            'name' => [
                'required',
                'string',
                // Rule::unique('floor_infos', 'name')->ignore($id),
            ],
            'building_id' => 'required'
        ]);

        if ($id > 0) {
            $floor = FloorInfo::whereId($request->id)->first();
            $message = 'Floor has been updated successfully';
        } else {
            $floor = new FloorInfo();
            $message = 'floor has been created successfully';
        }

        $floor->name = $request->name;
        $floor->building_id = $request->building_id;
        $floor->save();

        $building_id =  $request->building_id;

        $notify[] = ['success', $message];
        // return to_route('floor.index')->withNotify($notify);

        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $floor = FloorInfo::find($id);
        $floor->delete();
        // $floor->is_deleted = 1;
        // $floor->save();

        $notify[] = ['success', 'floor has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
