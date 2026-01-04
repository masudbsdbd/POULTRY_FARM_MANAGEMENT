<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\Unit;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ProductController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:product-list', ['only' => ['index']]);
        // $this->middleware('permission:product-create|product-edit', ['only' => ['store']]);
        // $this->middleware('permission:product-create', ['only' => ['create']]);
        // $this->middleware('permission:product-edit', ['only' => ['edit']]);
        // $this->middleware('permission:product-delete', ['only' => ['delete']]);
    }

    public function index()
    {
        $pageTitle = 'Product List';
        $products = Product::latest()->notDeleted()->paginate(gs()->pagination);
        return view('product.index', compact('pageTitle', 'products'));
    }

    public function printBarcode($id)
    {
        $product = Product::findOrFail($id);
        return view('product.barcode', compact('product'));
    }
    public function generateBarcode($code)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
        return response($barcode)->header('Content-Type', 'image/png');
    }

    public function create()
    {
        $pageTitle = 'Add Product';
        $categories = Category::whereStatus(1)->latest()->get();
        $subCategories = Subcategory::whereStatus(1)->latest()->get();
        $brands = Brand::whereStatus(1)->latest()->get();
        $units = Unit::whereStatus(1)->latest()->get();
        return view('product.create', compact('pageTitle', 'categories', 'subCategories', 'brands', 'units'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit Prdouct';
        $product = Product::find($id);
        $subCategories = Subcategory::whereCategoryId($product->category_id)->get();
        $categories = Category::whereStatus(1)->latest()->get();
        $brands = Brand::whereStatus(1)->latest()->get();
        $units = Unit::whereStatus(1)->latest()->get();
        return view('product.create', compact('pageTitle', 'product', 'categories', 'subCategories', 'brands', 'units'));
    }

    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            // 'category_id'       => 'required',
            // 'barcode'           => 'required|unique:products,barcode',
            // 'sub_category_id'   => 'nullable',
            // 'brand_id'          => 'nullable',
            'unit_id'           => 'required',
            'price'             => 'required',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($id > 0) {
            $product = Product::whereId($id)->first();
            $message = 'Product has been updated successfully';

            if ($request->file('image')) {
                $uploadedProductImage = uploadImage($request->file('image'), 'products', 450,  $product->image);
                $product->image             = $uploadedProductImage;
            }


            $givenStatus = isset($request->status) ? 1 : 0;
        } else {
            $product                = new Product();
            $message                = 'Product has been created successfully';
            $givenStatus            = isset($request->status) ? 1 : 0;

            if ($request->hasFile('image')) {
                $uploadedProductImage = uploadImage($request->file('image'), 'products', 450);
                $product->image             = $uploadedProductImage;
            }

            $product->code          = randomCode('PR');
        }

        $product->name              = $request->name;
        // $product->category_id       = $request->category_id;
        // $product->sub_category_id   = $request->sub_category_id;
        // $product->brand_id          = $request->brand_id;
        $product->unit_id           = $request->unit_id;
        $product->barcode           = $request->barcode;
        $product->price             = $request->price;
        $product->description       = $request->description;

        $product->status            = $givenStatus;
        $product->save();

        $notify[] = ['success', $message];
        return to_route('product.index')->withNotify($notify);
    }

    public function delete($id)
    {
        $product = Product::find($id);
        $product->delete();
        // $product->is_deleted = 1;
        // $product->save();

        $notify[] = ['success', 'Product has been successfully deleted'];
        return back()->withNotify($notify);
    }
}
