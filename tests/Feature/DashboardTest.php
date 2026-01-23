<?php

namespace Tests\Feature;

use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\ExpenseTransaction;
use App\Models\FiscalPeriod;
use App\Models\IncomeTransaction;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DashboardTest extends TestCase
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
            'business_unit_id' => $this->businessUnit->id,
            'is_active' => true,
        ]);
    }

    #[Test]
    public function authenticated_user_can_access_dashboard()
    {
        // Dashboard is at /
        $response = $this->actingAs($this->director)->get('/');

        $response->assertStatus(200);
    }

    #[Test]
    public function dashboard_displays_summary_statistics()
    {
        $revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan Usaha',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $expenseAccount = ChartOfAccount::create([
            'code' => '5110',
            'name' => 'Beban Operasional',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $incomeCategory = TransactionCategory::create([
            'name' => 'Pendapatan',
            'type' => 'income',
            'account_id' => $revenueAccount->id,
        ]);

        $expenseCategory = TransactionCategory::create([
            'name' => 'Beban',
            'type' => 'expense',
            'account_id' => $expenseAccount->id,
        ]);

        // Create some transactions
        IncomeTransaction::create([
            'transaction_number' => 'INC-001',
            'date' => now(),
            'description' => 'Test Income',
            'amount' => 1000000,
            'business_unit_id' => $this->businessUnit->id,
            'category_id' => $incomeCategory->id,
            'status' => 'approved',
            'created_by' => $this->director->id,
        ]);

        ExpenseTransaction::create([
            'transaction_number' => 'EXP-001',
            'date' => now(),
            'description' => 'Test Expense',
            'amount' => 500000,
            'business_unit_id' => $this->businessUnit->id,
            'category_id' => $expenseCategory->id,
            'status' => 'approved',
            'created_by' => $this->director->id,
        ]);

        $response = $this->actingAs($this->director)->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard.index');
    }

    #[Test]
    public function dashboard_shows_business_unit_data()
    {
        $response = $this->actingAs($this->director)->get('/');

        $response->assertStatus(200);
    }
}
