<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsertProductsRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductsController extends Controller
{
    public function list(): View
    {
        return view('products.list', [
            "products" => Products::all()
        ]);
    }

    public function show(string $id)
    {
        return response()->json([
            "product" => Products::findOrFail($id)
        ]);
    }

    public function insert(InsertProductsRequest $request)
    {
        $product = new Products;

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;

        if ($product->save()) {
            return response()->json([
                "message" => __("products.insert")
            ], 201);
        }

        return response()->json([
            "message" => __("products.err_insert")
        ], 400);
    }


    public function update(UpdateProductRequest $request)
    {

        $product = Products::find($request->id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;

        if ($product->save()) {
            return response()->json([
                "message" => __("products.update")
            ], 200);
        }

        return response()->json([
            "message" => __("products.err_update")
        ], 400);
    }

    public function delete(string $id)
    {
        $product = Products::find($id);
        if (empty($product)) return response()->json([
            'message' => __("products.notfound")
        ], 404);

        $deleted = Products::where('id', $id)->delete();
        return response()->json([
            "message" => $deleted ? __("products.delete") : __("products.err_delete"),
        ], $deleted ? 200 : 400);
    }

    public function filter(Request $request)
    {
        $name = $request->query('name');

        $products = Products::when($name, function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })->get();

        return view('products.list', compact('products'));
    }
}
