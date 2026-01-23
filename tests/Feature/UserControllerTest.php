<?php

namespace Tests\Feature;

use App\Models\BusinessUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $director;
    protected User $treasurer;
    protected BusinessUnit $businessUnit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Unit',
            'is_active' => true,
        ]);

        $this->director = User::create([
            'name' => 'Direktur',
            'email' => 'direktur@example.com',
            'password' => bcrypt('password'),
            'role' => 'director',
            'is_active' => true,
        ]);

        $this->treasurer = User::create([
            'name' => 'Bendahara',
            'email' => 'bendahara@example.com',
            'password' => bcrypt('password'),
            'role' => 'treasurer',
            'is_active' => true,
        ]);
    }

    #[Test]
    public function director_can_view_user_index()
    {
        $response = $this->actingAs($this->director)->get('/user');

        $response->assertStatus(200);
    }

    #[Test]
    public function director_can_view_create_form()
    {
        $response = $this->actingAs($this->director)->get('/user/create');

        $response->assertStatus(200);
    }

    #[Test]
    public function director_can_create_user()
    {
        $response = $this->actingAs($this->director)->post('/user', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'unit_admin',
            'business_unit_id' => $this->businessUnit->id,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'role' => 'unit_admin',
        ]);
    }

    #[Test]
    public function director_can_toggle_user_status()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'unit_admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->director)->post("/user/{$user->id}/toggle");

        $user->refresh();
        $this->assertFalse($user->is_active);
    }

    #[Test]
    public function non_director_cannot_access_user_management()
    {
        $response = $this->actingAs($this->treasurer)->get('/user');

        $response->assertStatus(403);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_user_management()
    {
        $response = $this->get('/user');

        $response->assertRedirect('/login');
    }
}
