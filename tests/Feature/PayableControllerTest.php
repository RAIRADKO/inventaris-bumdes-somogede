<?php

namespace Tests\Feature;

use App\Models\BusinessUnit;
use App\Models\Payable;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PayableControllerTest extends TestCase
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
    public function authenticated_user_can_view_payable_index()
    {
        $response = $this->actingAs($this->director)->get('/payable');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_view_create_form()
    {
        $response = $this->actingAs($this->director)->get('/payable/create');

        $response->assertStatus(200);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_payables()
    {
        $response = $this->get('/payable');

        $response->assertRedirect('/login');
    }
}
