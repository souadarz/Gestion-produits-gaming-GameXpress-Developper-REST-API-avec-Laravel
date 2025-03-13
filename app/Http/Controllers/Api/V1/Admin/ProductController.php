<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $products = Product::all();
        return $products;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required',
            'sub_categorie_id' => 'required|integer'
        ]);

        // return $request;
        $product = Product::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->status,
            'sub_categorie_id' => $request->sub_categorie_id,
        ]);

        return [
            'product' => $product,
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::where('id', $id)->first();
        // $product = Product::findOrFail($id);

        return [
            'product' => $product,
        ];
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        // $validateData = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'slug' => 'required|string|max:255',
        //     'price' => 'required|numeric|min:0',
        //     'stock' => 'required|integer|min:0',
        //     'status' => 'required',
        //     'sub_categorie_id' => 'required|integer'
        // ]);

        // $product->update($validateData);
        $product->update([
            'name' => 'Produit up',
            'slug' => 'produit-up', 
            'price' => 26.5,
            'stock' => 84,
            'status' => 'disponible', 
            'sub_categorie_id' => 1 
        ]);

        return [
            'product' => $product,
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return [
            'message' => 'the product is deleted',
        ];
    }
}
