<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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

        $response->assertStatus(200)->assertJsonCount(3);
    }

    public function testStoreProduct()
    {
        $user = User::factory()->create();
        $user->assignRole("product_manager");
        $this->actingAs($user);
        $data = [
            'name' => 'test product',
            'price' => 58.98,
            'slug' => 'test-product',
            'stock' => 25,
            'status' => 'disponible',
            'sub_category_id' => 1,
        ];

        $response = $this->postJson(route('products.store'), $data);

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
                        // 'product_images' => [
                        //     '*' => ['image_url', 'is_primary']
                        // ]
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

    public function test_can_delete_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson(route('products.destroy', $product->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
