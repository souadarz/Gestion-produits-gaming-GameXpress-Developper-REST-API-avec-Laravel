<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

// use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testListUsers()
    {
        $user = User::factory()->create();
        $user->assignRole("user_manager");
        $this->actingAs($user);
        user::factory()->count(3)->create();
        $response = $this->getJson(route('users.index'));

        $response->assertStatus(200);
        // ->assertJsonCount(3);
    }

    public function testStoreUser()
    {
        $user = User::factory()->create();
        $user->assignRole("user_manager");
        $this->actingAs($user);
    
        $data = [
            'name' => 'user test',
            'email' => 'example6@email.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];
        
        $response = $this->postJson(route('users.store'),$data);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                            'name' => $data['name'],
                            'email' => $data['email'],
                         ]);

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email']
        ]);
    }

    public function testUpdateUser()
    {
        $user_manager = User::factory()->create();
        $user_manager->assignRole("user_manager");
        $this->actingAs($user_manager);
    
        $user = User::factory()->create();
        $Updatedata = [
                'name' => 'user test',
                'email' => 'example8@email.com',
                'password' => 'password',
                'password_confirmation' => 'password'
            ];
        
        $response = $this->putJson(route('users.update',$user->id),$Updatedata);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                            'name' => $Updatedata['name'],
                            'email' => $Updatedata['email'],
                         ]);

        $this->assertDatabaseHas('users', ['name' => $Updatedata['name'],'email' => $Updatedata['email']]);
    }

    public function testDeleteUser()
    {
        $user_manager = User::factory()->create();
        $user_manager->assignRole("user_manager");
        $this->actingAs($user_manager);
        $user = User::factory()->create();

        $response = $this->deleteJson(route('users.destroy', $user->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
