<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:category-list', ['only' => [
    //         'index'
    //     ]]);
    //     $this->middleware('permission:category-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:category-edit', ['only' => ['edit', 'store']]);
    //     $this->middleware('permission:category-delete', ['only' => ['delete']]);
    // }

    public function __construct()
    {
        $this->middleware('permission:category-list', ['only' => ['index']]);
        $this->middleware('permission:category-create|category-edit', ['only' => ['store']]);
        $this->middleware('permission:category-create', ['only' => ['create']]);
        $this->middleware('permission:category-edit', ['only' => ['edit']]);
        $this->middleware('permission:category-delete', ['only' => ['delete']]);
    }

    public function index()
    {
        $pageTitle = 'Category List';
        $categories = Category::latest()->notDeleted()->paginate(gs()->pagination);
        return view('category.index', compact('pageTitle', 'categories'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('categories', 'name')->ignore($id),
            ],
        ]);

        if ($id > 0) {
            $category = Category::whereId($request->id)->first();
            $message = 'Category has been updated successfully';
            $givenStatus = isset($request->editcatstatus) ? 1 : 0;
        } else {
            $category = new Category();
            $message = 'Category has been created successfully';
            $givenStatus = isset($request->status) ? 1 : 0;
        }

        $category->name = $request->name;
        $category->status = $givenStatus;
        $category->save();

        $notify[] = ['success', $message];
        return to_route('category.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $category = Category::find($id);
        $category->is_deleted = 1;
        $category->save();

        $notify[] = ['success', 'Category has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
