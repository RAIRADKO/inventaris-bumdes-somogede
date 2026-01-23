<?php

namespace Tests\Feature;

use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\ExpenseTransaction;
use App\Models\FiscalPeriod;
use App\Models\IncomeTransaction;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReportControllerTest extends TestCase
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
    public function authenticated_user_can_view_report_index()
    {
        $response = $this->actingAs($this->director)->get('/report');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_view_income_statement()
    {
        $response = $this->actingAs($this->director)->get('/report/income-statement');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_view_balance_sheet()
    {
        $response = $this->actingAs($this->director)->get('/report/balance-sheet');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_view_cash_flow()
    {
        $response = $this->actingAs($this->director)->get('/report/cash-flow');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_view_general_ledger()
    {
        $response = $this->actingAs($this->director)->get('/report/general-ledger');

        $response->assertStatus(200);
    }

    #[Test]
    public function authenticated_user_can_view_trial_balance()
    {
        $response = $this->actingAs($this->director)->get('/report/trial-balance');

        $response->assertStatus(200);
    }

    #[Test]
    public function income_statement_shows_correct_data()
    {
        // Create accounts
        $revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $expenseAccount = ChartOfAccount::create([
            'code' => '5110',
            'name' => 'Beban',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $cashAccount = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        // Create approved journal with entries
        $journal = Journal::create([
            'journal_number' => 'JRN-001',
            'date' => now(),
            'description' => 'Test',
            'business_unit_id' => $this->businessUnit->id,
            'fiscal_period_id' => $this->fiscalPeriod->id,
            'status' => 'approved',
            'type' => 'manual',
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'debit' => 1000000,
            'credit' => 0,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $revenueAccount->id,
            'debit' => 0,
            'credit' => 1000000,
        ]);

        $response = $this->actingAs($this->director)->get('/report/income-statement');

        $response->assertStatus(200);
    }

    #[Test]
    public function unauthenticated_user_cannot_access_reports()
    {
        $response = $this->get('/report');

        $response->assertRedirect('/login');
    }
}
