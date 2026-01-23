<?php

namespace Tests\Feature;

use App\Models\ChartOfAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ChartOfAccountControllerTest extends TestCase
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
    public function authenticated_user_can_view_chart_of_accounts_index()
    {
        // Route is /chart-of-account (singular)
        $response = $this->actingAs($this->director)->get('/chart-of-account');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_view_create_form()
    {
        $response = $this->actingAs($this->director)->get('/chart-of-account/create');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_create_a_chart_of_account()
    {
        $response = $this->actingAs($this->director)->post('/chart-of-account', [
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('chart_of_accounts', [
            'code' => '1110',
            'name' => 'Kas',
        ]);
    }

    #[Test]
    public function it_validates_required_fields_when_creating()
    {
        $response = $this->actingAs($this->director)->post('/chart-of-account', [
            // Missing required fields
        ]);

        $response->assertSessionHasErrors(['code', 'name', 'type']);
    }

    #[Test]
    public function it_can_update_a_chart_of_account()
    {
        $account = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->director)->put("/chart-of-account/{$account->id}", [
            'code' => '1110',
            'name' => 'Kas Besar',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('chart_of_accounts', [
            'id' => $account->id,
            'name' => 'Kas Besar',
        ]);
    }

    #[Test]
    public function it_can_delete_a_chart_of_account()
    {
        $account = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->director)->delete("/chart-of-account/{$account->id}");

        $this->assertDatabaseMissing('chart_of_accounts', [
            'id' => $account->id,
        ]);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_chart_of_accounts()
    {
        $response = $this->get('/chart-of-account');

        $response->assertRedirect('/login');
    }
}
