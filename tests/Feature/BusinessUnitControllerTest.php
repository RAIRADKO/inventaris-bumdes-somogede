<?php

namespace Tests\Feature;

use App\Models\BusinessUnit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BusinessUnitControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $director;

    protected function setUp(): void
    {
        parent::setUp();

        $this->director = User::create([
            'name' => 'Direktur',
            'email' => 'direktur@example.com',
            'password' => bcrypt('password'),
            'role' => 'director',
            'is_active' => true,
        ]);
    }

    #[Test]
    public function authenticated_user_can_view_business_unit_index()
    {
        $response = $this->actingAs($this->director)->get('/business-unit');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_view_create_form()
    {
        $response = $this->actingAs($this->director)->get('/business-unit/create');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_create_business_unit()
    {
        $response = $this->actingAs($this->director)->post('/business-unit', [
            'code' => 'BU001',
            'name' => 'Unit Perdagangan',
            'description' => 'Unit usaha perdagangan',
            'is_active' => true,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('business_units', [
            'code' => 'BU001',
            'name' => 'Unit Perdagangan',
        ]);
    }

    #[Test]
    public function it_can_update_business_unit()
    {
        $unit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Original',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->director)->put("/business-unit/{$unit->id}", [
            'code' => 'BU001',
            'name' => 'Updated Name',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('business_units', [
            'id' => $unit->id,
            'name' => 'Updated Name',
        ]);
    }

    #[Test]
    public function it_can_toggle_active_status()
    {
        $unit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Unit',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->director)->post("/business-unit/{$unit->id}/toggle");

        $unit->refresh();
        $this->assertFalse($unit->is_active);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_business_units()
    {
        $response = $this->get('/business-unit');

        $response->assertRedirect('/login');
    }
}
