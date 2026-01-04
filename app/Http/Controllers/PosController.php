<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Customer;

class PosController extends Controller
{
    //
    public function index()
    {
        $pageTitle = 'Pos Mode';

        $stockProductIds = Stock::all()->pluck('product_id')->unique()->toArray();
        $customers = Customer::whereStatus(1)->notDeleted()->latest()->get();

        // $products = Product::whereIn('id', $stockProductIds)
        //     ->with('stocks.purchaseBatch')
        //     ->latest()
        //     ->notDeleted()
        //     ->inRandomOrder()
        //     ->limit(10)
        //     ->get();


        
        $products = Product::whereIn('id', $stockProductIds)
            ->with('stocks.purchaseBatch')
            ->with(['stocks' => function ($q) {
                $q->where('stock', '>', 0);
            }])
            ->latest()
            ->notDeleted()
            ->inRandomOrder()
            ->limit(10)
            ->get();



        $productData = $products->mapWithKeys(function ($product) {
            return [
                $product->barcode => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category_id' => $product->category_id,
                    'sub_category_id' => $product->sub_category_id,
                    'brand_id' => $product->brand_id,
                    'unit_id' => $product->unit_id,
                    'code' => $product->code,
                    'price' => $product->price,
                    'description' => $product->description,
                    'image' => $product->image,
                    'status' => $product->status,
                    'is_deleted' => $product->is_deleted,
                    'created_at' => $product->created_at->toISOString(),
                    'updated_at' => $product->updated_at->toISOString(),
                    'stocks' => $product->stocks->toArray(),
                ],
            ];
        });

        // dd($products);
        // dd($productData);

        return view('pos.index', compact('products', 'pageTitle', 'customers', 'productData'));
    }

    public function getProductSuggestions(Request $request, $id = 0)
    {
        $term = $request->input('term');
        $barcode = $request->input('barcode');

        $stockProductIds = Stock::all()->pluck('product_id')->unique()->toArray();

        $products = Product::whereIn('id', $stockProductIds)
            ->where('name', 'like', "%{$term}%")
            ->with('stocks')
            ->with('stocks.purchaseBatch', 'stockItem')
            ->limit(5)
            ->get(['id', 'name', 'price']);


        return response()->json($products);
    }
}
