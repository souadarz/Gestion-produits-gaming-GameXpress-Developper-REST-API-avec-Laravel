<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    // use RefreshDatabase;

    public function testListProducts()
    {
        $user = User::factory()->create();
        $user->assignRole("product_manager");
        $this->actingAs($user);
        Product::factory()->count(3)->create();
        $response = $this->getJson(route('products.index'));

        $response->assertStatus(200);
        // ->assertJsonCount(3);
    }

    public function testStoreProduct()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $user->assignRole("product_manager");
        $this->actingAs($user);
        // dd($user);
        $data = [
            'name' => 'test product',
            'price' => 58.98,
            'slug' => 'test-product',
            'stock' => 25,
            'status' => 'disponible',
            'sub_category_id' => 1,
        ];
        
        $images = [
            UploadedFile::fake()->image('image_test1.png'),
            UploadedFile::fake()->image('image_test2.png'),
        ];
        $response = $this->postJson(route('products.store'), array_merge($data, ['images' => $images]));

        $response->assertStatus(200)
                 ->assertJsonFragment([
                            'name' => $data['name'],
                            'price' => 58.98,
                            'slug' => 'test-product',
                            'stock' => 25,
                            'status' => 'disponible',
                            'sub_category_id' => 1,
                        ]);

        $this->assertDatabaseHas('products', $data);
    }

    public function testShowProduct()
    {
        $user = User::factory()->create();
        $user->assignRole("product_manager");
        $this->actingAs($user);
        $product = Product::factory()->create();

        $response = $this->getJson(route('product.show', $product->id));

        // $response->assertStatus(200)
        //          ->assertJson(['id' => $product->id]);
        $response->assertStatus(200)->assertJsonStructure([
                    'product' => [
                        'id', 'name', 'slug', 'price', 'stock', 'status', 'sub_category_id',
                    ]
                ]);
    }

    public function testUpdateProduct()
    {
        $user = User::factory()->create();
        $user->assignRole("product_manager");
        $this->actingAs($user);

        $product = Product::factory()->create();

        $updateData = [
            'name' => 'updated product',
            'price' => 58.98,
            'slug' => 'updated-product',
            'stock' => 25,
            'status' => 'disponible',
            'sub_category_id' => 1];

        $response = $this->putJson(route('product.update', $product->id), $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                        'name' => $updateData['name'],
                            'price' => $updateData['price'],
                            'slug' => $updateData['slug'],
                            'stock' => $updateData['stock'],
                            'status' => $updateData['status'],
                            'sub_category_id' => $updateData['sub_category_id']
                        ]);
        $this->assertDatabaseHas('products', $updateData);
    }

    public function testDeleteProduct()
    {
        $user = User::factory()->create();
        $user->assignRole("product_manager");
        $this->actingAs($user);
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('product.destroy', $product->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
