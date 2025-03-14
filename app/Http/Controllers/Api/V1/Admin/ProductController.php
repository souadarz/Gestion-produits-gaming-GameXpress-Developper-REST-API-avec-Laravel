<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Product_images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  
        $products = Product::all();
        return $products->load('product_images');
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
            'sub_category_id' => 'required|integer',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // return $request;
        $product = Product::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->status,
            'sub_category_id' => $request->sub_category_id,
        ]);
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');
    
                Product_images::create([
                    'product_id' => $product->id,
                    'image_url' => $imagePath,
                    'is_primary' => $index === 0 ? true : false
                ]);
            }
        }
        return [
            'product' => $product->load('product_images'),
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
            'product' => $product->load('product_images'),
        ];
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'status' => 'sometimes',
            'sub_category_id' => 'sometimes|integer',
            'imageToDelete' => 'sometimes|exists:Product_images,image_url',
            'primaryImage' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product->update($request->except(['images']));

        if($request->imageToDelete){
            foreach($request->imageToDelete as $imageDelete){
                Storage::disk('public')->delete($imageDelete);
            }
            $product->product_images()->whereIn('image_url',$request->imageToDelete)->delete();
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('products', 'public');
    
                Product_images::create([
                    'product_id' => $product->id,
                    'image_url' => $imagePath,
                    'is_primary' => false
                ]);
            }
        }
        if($request->hasFile('primaryImage')){
            $image = $request->file('primaryImage');
            $imagePath = $image->store('products', 'public');
    
            Product_images::create([
                'product_id' => $product->id,
                'image_url' => $imagePath,
                'is_primary' => true
            ]);
        }

        return [
            'product' => $product->load('product_images'),
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
