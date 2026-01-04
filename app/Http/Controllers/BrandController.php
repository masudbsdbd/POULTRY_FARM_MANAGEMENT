<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:brand-list', ['only' => ['index']]);
        $this->middleware('permission:brand-create|category-edit', ['only' => ['store']]);
        $this->middleware('permission:brand-create', ['only' => ['create']]);
        $this->middleware('permission:brand-edit', ['only' => ['edit']]);
        $this->middleware('permission:brand-delete', ['only' => ['delete']]);
    }
    public function index()
    {
        $pageTitle = 'Brand List';
        $brands = Brand::latest()->notDeleted()->paginate(gs()->pagination);
        return view('brand.index', compact('pageTitle', 'brands'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('brands', 'name')->ignore($id),
            ],
        ]);

        if ($id > 0) {
            $brand = Brand::whereId($request->id)->first();
            $message = 'Brand has been updated successfully';
            $givenStatus = isset($request->editbrandstatus) ? 1 : 0;
        } else {
            $brand = new Brand();
            $message = 'Brand has been created successfully';
            $givenStatus = isset($request->status) ? 1 : 0;
        }

        $brand->name = $request->name;
        $brand->status = $givenStatus;
        $brand->save();

        $notify[] = ['success', $message];
        return to_route('brand.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $brand = Brand::find($id);
        $brand->is_deleted = 1;
        $brand->save();

        $notify[] = ['success', 'Brand has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
