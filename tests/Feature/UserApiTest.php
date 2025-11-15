<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    /**
     * A basic feature test example.
     */

 use RefreshDatabase;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
 public function test_api_create_user()
{
    $admin = User::factory()->create([
        'role' => 'admin'
    ]);

    Sanctum::actingAs($admin);

    $data = [
        "nom" => "laalam",
        "prenom" => "mouad",
        "email" => "mouad@gmail.com",
        "password" => "123456789",
        
    ];

    $response = $this->postJson('/api/users', $data);

    $response->assertStatus(201);

    
    $this->assertDatabaseHas('users', [
        "nom" => "laalam",
        "prenom" => "mouad",
        "email" => "mouad@gmail.com",
    ]);
}


    public function test_api_retourne_tous_les_users()
    {
        
        $admin = User::factory()->create([
              'role' => 'admin'
          ]);
          

        Sanctum::actingAs($admin);
        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }


   
    public function test_api_met_a_jour_un_post()
{
    $admin = User::factory()->create(['role' => 'admin']);
    Sanctum::actingAs($admin);

    $user = User::factory()->create();

    $update = [
        'nom'    => 'Updated nom',
        'prenom' => 'mouad',
        'email'  => 'mouad@gmail.com',
        'password' => '123456789',
        'role'   => 'visitor'
    ];

    $response = $this->putJson("/api/users/{$user->id}", $update);

    $response->assertStatus(200);

    
    $response->assertJsonFragment([
        'nom' => 'Updated nom',
        'prenom' => 'mouad',
        'email' => 'mouad@gmail.com',
        'role' => 'visitor',
    ]);


    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'nom' => 'Updated nom',
        'prenom' => 'mouad',
        'email' => 'mouad@gmail.com',
        'role' => 'visitor',
    ]);

 
    $this->assertTrue(
        Hash::check('123456789', User::find($user->id)->password)
    );
}

public function test_api_delete_user()
    {
          $admin = User::factory()->create([
                    'role' => 'admin'
                ]);

        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/users/{$admin->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'user deleted successfully.']);

        $this->assertDatabaseMissing('users', ['id' => $admin->id]);
    }



}
