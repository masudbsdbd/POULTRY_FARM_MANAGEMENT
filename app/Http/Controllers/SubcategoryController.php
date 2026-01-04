<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Validation\Rule;

class SubcategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sub-category-list', ['only' => ['index']]);
        $this->middleware('permission:sub-category-create|sub-category-edit', ['only' => ['store']]);
        $this->middleware('permission:sub-category-create', ['only' => ['create']]);
        $this->middleware('permission:sub-category-edit', ['only' => ['edit']]);
        $this->middleware('permission:sub-category-delete', ['only' => ['delete']]);
    }
    public function index()
    {
        $pageTitle = 'Subcategory List';
        $subcategories = Subcategory::latest()->notDeleted()->paginate(gs()->pagination);
        $categories = Category::latest()->notDeleted()->get();
        return view('subcategory.index', compact('pageTitle', 'subcategories', 'categories'));
    }

    public function subcatAjax(Request $request)
    {
        $subcategories = Subcategory::whereCategoryId($request->id)->active()->notDeleted()->get();
        return $subcategories;
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('subcategories', 'name')->ignore($id),
            ],
            'category_id' => 'required'
        ]);

        if ($id > 0) {
            $subcategory = Subcategory::whereId($request->id)->first();
            $message = 'Subcategory has been updated successfully';
            $givenStatus = isset($request->editsubcatstatus) ? 1 : 0;
        } else {
            $subcategory = new Subcategory();
            $message = 'Subcategory has been created successfully';
            $givenStatus = isset($request->status) ? 1 : 0;
        }

        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;
        $subcategory->status = $givenStatus;
        $subcategory->save();

        $notify[] = ['success', $message];
        return to_route('subcategory.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $subcategory = Subcategory::find($id);
        $subcategory->is_deleted = 1;
        $subcategory->save();

        $notify[] = ['success', 'Subcategory has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
