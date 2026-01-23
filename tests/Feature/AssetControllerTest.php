<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\BusinessUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AssetControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $director;
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
    }

    #[Test]
    public function authenticated_user_can_view_asset_index()
    {
        $response = $this->actingAs($this->director)->get('/asset');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_view_create_form()
    {
        $response = $this->actingAs($this->director)->get('/asset/create');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_view_asset_categories()
    {
        $response = $this->actingAs($this->director)->get('/asset/categories');

        $response->assertStatus(200);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_assets()
    {
        $response = $this->get('/asset');

        $response->assertRedirect('/login');
    }
}
