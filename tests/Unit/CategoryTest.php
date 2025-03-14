<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\User;
use Tests\TestCase;

// use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testListcategories()
    {
        $user = User::factory()->create();
        $user->assignRole("product_manager");
        $this->actingAs($user);
        Category::factory()->count(3)->create();
        $response = $this->getJson(route('categories.index'));

        $response->assertStatus(200);
        // ->assertJsonCount(3);
    }

    public function testStoreCategory()
    {
        $user = User::factory()->create();
        $user->assignRole("product_manager");
        $this->actingAs($user);
    
        $data = [
            'name' => 'test category',
            'slug' => 'test-category',
        ];
        
        $response = $this->postJson(route('categories.store'),$data);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                            'name' => $data['name'],
                            'slug' => $data['slug'],
                         ]);

        $this->assertDatabaseHas('categories', $data);
    }

    public function testUpdateCategory()
    {
        $user = User::factory()->create();
        $user->assignRole("product_manager");
        $this->actingAs($user);
    
        $category = Category::factory()->create();
        $Updatedata = [
            'name' => 'test category',
            'slug' => 'test-category',
        ];
        
        $response = $this->putJson(route('category.update',$category->id),$Updatedata);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                            'name' => $Updatedata['name'],
                            'slug' => $Updatedata['slug'],
                         ]);

        $this->assertDatabaseHas('categories', $Updatedata);
    }

    public function testDeletecategory()
    {
        $user = User::factory()->create();
        $user->assignRole("product_manager");
        $this->actingAs($user);
        $category = Category::factory()->create();

        $response = $this->deleteJson(route('category.destroy', $category->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
