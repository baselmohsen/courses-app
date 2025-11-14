<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_category()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $token = $admin->createToken('token')->plainTextToken;

        $response = $this->withHeaders(['Authorization'=>"Bearer $token"])
                         ->postJson('/api/categories', ['name'=>'Programming']);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name'=>'Programming']);

        $this->assertDatabaseHas('categories', ['name'=>'Programming']);
    }

    public function test_non_admin_cannot_create_category()
    {
        $user = User::factory()->create(['role' => 'student']);
        $token = $user->createToken('token')->plainTextToken;

        $response = $this->withHeaders(['Authorization'=>"Bearer $token"])
                         ->postJson('/api/categories', ['name'=>'Math']);

        $response->assertStatus(403);
    }
}
