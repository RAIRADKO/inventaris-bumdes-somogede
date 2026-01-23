<?php

namespace Tests\Feature;

use App\Models\Budget;
use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\FiscalPeriod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BudgetControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $director;
    protected BusinessUnit $businessUnit;
    protected FiscalPeriod $fiscalPeriod;

    protected function setUp(): void
    {
        parent::setUp();

        $this->businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Unit',
            'is_active' => true,
        ]);

        $this->fiscalPeriod = FiscalPeriod::create([
            'name' => 'Tahun 2026',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
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
    public function authenticated_user_can_view_budget_index()
    {
        $response = $this->actingAs($this->director)->get('/budget');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_view_create_form()
    {
        $response = $this->actingAs($this->director)->get('/budget/create');

        $response->assertStatus(200);
    }

    #[Test]
    public function it_can_create_budget()
    {
        $revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->director)->post('/budget', [
            'name' => 'Anggaran 2026',
            'fiscal_period_id' => $this->fiscalPeriod->id,
            'business_unit_id' => $this->businessUnit->id,
            'description' => 'Test budget',
            'items' => [
                [
                    'account_id' => $revenueAccount->id,
                    'planned_amount' => 10000000,
                    'description' => 'Target Pendapatan',
                ],
            ],
        ]);

        $this->assertDatabaseHas('budgets', [
            'name' => 'Anggaran 2026',
        ]);
    }

    #[Test]
    public function director_can_approve_budget()
    {
        $budget = Budget::create([
            'name' => 'Anggaran Test',
            'fiscal_period_id' => $this->fiscalPeriod->id,
            'business_unit_id' => $this->businessUnit->id,
            'status' => 'draft',
            'created_by' => $this->director->id,
        ]);

        $response = $this->actingAs($this->director)->post("/budget/{$budget->id}/approve");

        $budget->refresh();
        $this->assertEquals('approved', $budget->status);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_budgets()
    {
        $response = $this->get('/budget');

        $response->assertRedirect('/login');
    }
}
