<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_register_user()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Basel',
            'email' => 'basel@example.com',
            'password' => 'password',
            'role' => 'student' // ← في جدول users
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user' => ['id','name','email','profile'],
                     'token'
                 ]);

        // تحقق من أن المستخدم موجود مع الدور الصحيح
        $this->assertDatabaseHas('users', [
            'email' => 'basel@example.com',
            'role' => 'student'
        ]);

        // تحقق من أن الـ profile موجود بدون role
        $userId = User::where('email', 'basel@example.com')->first()->id;
        $this->assertDatabaseHas('profiles', [
            'user_id' => $userId
        ]);
    }

    /** @test */
    public function test_login_user()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'role' => 'student' // ← دور في جدول users
        ]);

        Profile::factory()->create(['user_id' => $user->id]); // Profile بدون role

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'user' => ['id','name','email','profile'],
                     'token'
                 ]);
    }

    /** @test */
    public function test_authenticated_user_can_access_protected_route()
    {
        $user = User::factory()->create([
            'role' => 'student'
        ]);

        // Use Sanctum to authenticate this user for the request
        Sanctum::actingAs($user, ['*']); // ← هنا يتم تمثيل المستخدم كأنه مسجل الدخول

        $response = $this->getJson('/api/user');

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $user->id,
                     'email' => $user->email
                 ]);
    }
}
