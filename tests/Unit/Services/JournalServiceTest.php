<?php

namespace Tests\Unit\Services;

use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\ExpenseTransaction;
use App\Models\FiscalPeriod;
use App\Models\IncomeTransaction;
use App\Models\CashTransaction;
use App\Models\Journal;
use App\Models\TransactionCategory;
use App\Models\User;
use App\Services\JournalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class JournalServiceTest extends TestCase
{
    use RefreshDatabase;

    protected JournalService $journalService;
    protected BusinessUnit $businessUnit;
    protected FiscalPeriod $fiscalPeriod;
    protected User $user;
    protected ChartOfAccount $cashAccount;
    protected ChartOfAccount $revenueAccount;
    protected ChartOfAccount $expenseAccount;
    protected TransactionCategory $incomeCategory;
    protected TransactionCategory $expenseCategory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->journalService = new JournalService();

        // Create business unit
        $this->businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Business Unit',
            'is_active' => true,
        ]);

        // Create fiscal period
        $this->fiscalPeriod = FiscalPeriod::create([
            'name' => 'Tahun 2026',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_active' => true,
            'is_closed' => false,
        ]);

        // Create user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'director',
        ]);

        // Create chart of accounts
        $this->cashAccount = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $this->revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan Usaha',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $this->expenseAccount = ChartOfAccount::create([
            'code' => '5110',
            'name' => 'Beban Operasional',
            'type' => 'expense',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        // Create transaction categories linked to accounts
        $this->incomeCategory = TransactionCategory::create([
            'name' => 'Pendapatan Penjualan',
            'type' => 'income',
            'account_id' => $this->revenueAccount->id,
        ]);

        $this->expenseCategory = TransactionCategory::create([
            'name' => 'Beban ATK',
            'type' => 'expense',
            'account_id' => $this->expenseAccount->id,
        ]);
    }

    #[Test]
    public function it_creates_journal_from_income_transaction()
    {
        $incomeTransaction = IncomeTransaction::create([
            'transaction_number' => 'INC-001',
            'date' => '2026-01-23',
            'description' => 'Penjualan Produk',
            'amount' => 1000000,
            'business_unit_id' => $this->businessUnit->id,
            'category_id' => $this->incomeCategory->id,
            'status' => 'approved',
            'created_by' => $this->user->id,
            'approved_by' => $this->user->id,
            'approved_at' => now(),
        ]);

        $journal = $this->journalService->createFromIncome($incomeTransaction);

        $this->assertNotNull($journal);
        $this->assertInstanceOf(Journal::class, $journal);
        $this->assertStringContainsString('Pendapatan:', $journal->description);
        $this->assertEquals('approved', $journal->status);
        $this->assertEquals('auto', $journal->type);

        // Verify journal entries
        $this->assertCount(2, $journal->entries);

        // Verify debit entry (Cash)
        $debitEntry = $journal->entries->where('debit', '>', 0)->first();
        $this->assertEquals($this->cashAccount->id, $debitEntry->account_id);
        $this->assertEquals(1000000, $debitEntry->debit);

        // Verify credit entry (Revenue)
        $creditEntry = $journal->entries->where('credit', '>', 0)->first();
        $this->assertEquals($this->revenueAccount->id, $creditEntry->account_id);
        $this->assertEquals(1000000, $creditEntry->credit);

        // Verify journal is balanced
        $this->assertTrue($journal->isBalanced());
    }

    #[Test]
    public function it_creates_journal_from_expense_transaction()
    {
        $expenseTransaction = ExpenseTransaction::create([
            'transaction_number' => 'EXP-001',
            'date' => '2026-01-23',
            'description' => 'Pembelian ATK',
            'amount' => 500000,
            'business_unit_id' => $this->businessUnit->id,
            'category_id' => $this->expenseCategory->id,
            'status' => 'approved',
            'created_by' => $this->user->id,
            'approved_by' => $this->user->id,
            'approved_at' => now(),
        ]);

        $journal = $this->journalService->createFromExpense($expenseTransaction);

        $this->assertNotNull($journal);
        $this->assertInstanceOf(Journal::class, $journal);
        $this->assertStringContainsString('Pengeluaran:', $journal->description);
        $this->assertEquals('approved', $journal->status);

        // Verify journal entries
        $this->assertCount(2, $journal->entries);

        // Verify debit entry (Expense)
        $debitEntry = $journal->entries->where('debit', '>', 0)->first();
        $this->assertEquals($this->expenseAccount->id, $debitEntry->account_id);
        $this->assertEquals(500000, $debitEntry->debit);

        // Verify credit entry (Cash)
        $creditEntry = $journal->entries->where('credit', '>', 0)->first();
        $this->assertEquals($this->cashAccount->id, $creditEntry->account_id);
        $this->assertEquals(500000, $creditEntry->credit);

        // Verify journal is balanced
        $this->assertTrue($journal->isBalanced());
    }

    #[Test]
    public function it_creates_journal_from_cash_in_transaction()
    {
        // Use 'income' type since database only allows income/expense
        $cashCategory = TransactionCategory::create([
            'name' => 'Penerimaan Lainnya',
            'type' => 'income',
            'account_id' => $this->revenueAccount->id,
        ]);

        $cashTransaction = CashTransaction::create([
            'transaction_number' => 'CASH-001',
            'date' => '2026-01-23',
            'type' => 'in',
            'description' => 'Penerimaan Kas',
            'amount' => 750000,
            'business_unit_id' => $this->businessUnit->id,
            'category_id' => $cashCategory->id,
            'status' => 'approved',
            'created_by' => $this->user->id,
            'approved_by' => $this->user->id,
            'approved_at' => now(),
        ]);

        $journal = $this->journalService->createFromCash($cashTransaction);

        $this->assertNotNull($journal);
        $this->assertStringContainsString('Kas Masuk:', $journal->description);
        $this->assertTrue($journal->isBalanced());

        // For cash in: Debit Cash, Credit Revenue
        $debitEntry = $journal->entries->where('debit', '>', 0)->first();
        $this->assertEquals($this->cashAccount->id, $debitEntry->account_id);

        $creditEntry = $journal->entries->where('credit', '>', 0)->first();
        $this->assertEquals($this->revenueAccount->id, $creditEntry->account_id);
    }

    #[Test]
    public function it_creates_journal_from_cash_out_transaction()
    {
        // Use 'expense' type since database only allows income/expense
        $cashCategory = TransactionCategory::create([
            'name' => 'Pengeluaran Lainnya',
            'type' => 'expense',
            'account_id' => $this->expenseAccount->id,
        ]);

        $cashTransaction = CashTransaction::create([
            'transaction_number' => 'CASH-002',
            'date' => '2026-01-23',
            'type' => 'out',
            'description' => 'Pengeluaran Kas',
            'amount' => 250000,
            'business_unit_id' => $this->businessUnit->id,
            'category_id' => $cashCategory->id,
            'status' => 'approved',
            'created_by' => $this->user->id,
            'approved_by' => $this->user->id,
            'approved_at' => now(),
        ]);

        $journal = $this->journalService->createFromCash($cashTransaction);

        $this->assertNotNull($journal);
        $this->assertStringContainsString('Kas Keluar:', $journal->description);
        $this->assertTrue($journal->isBalanced());

        // For cash out: Debit Expense, Credit Cash
        $debitEntry = $journal->entries->where('debit', '>', 0)->first();
        $this->assertEquals($this->expenseAccount->id, $debitEntry->account_id);

        $creditEntry = $journal->entries->where('credit', '>', 0)->first();
        $this->assertEquals($this->cashAccount->id, $creditEntry->account_id);
    }

    #[Test]
    public function it_returns_null_when_no_cash_account_exists()
    {
        // Delete cash account
        $this->cashAccount->delete();

        $incomeTransaction = IncomeTransaction::create([
            'transaction_number' => 'INC-002',
            'date' => '2026-01-23',
            'description' => 'Test Transaction',
            'amount' => 100000,
            'business_unit_id' => $this->businessUnit->id,
            'category_id' => $this->incomeCategory->id,
            'status' => 'approved',
            'created_by' => $this->user->id,
        ]);

        $journal = $this->journalService->createFromIncome($incomeTransaction);

        $this->assertNull($journal);
    }

    #[Test]
    public function it_returns_null_when_no_category_account_linked()
    {
        // Create category without account link
        $categoryWithoutAccount = TransactionCategory::create([
            'name' => 'No Account Category',
            'type' => 'income',
            'account_id' => null,
        ]);

        $incomeTransaction = IncomeTransaction::create([
            'transaction_number' => 'INC-003',
            'date' => '2026-01-23',
            'description' => 'Test Transaction',
            'amount' => 100000,
            'business_unit_id' => $this->businessUnit->id,
            'category_id' => $categoryWithoutAccount->id,
            'status' => 'approved',
            'created_by' => $this->user->id,
        ]);

        $journal = $this->journalService->createFromIncome($incomeTransaction);

        $this->assertNull($journal);
    }

    #[Test]
    public function it_updates_transaction_with_journal_reference()
    {
        $incomeTransaction = IncomeTransaction::create([
            'transaction_number' => 'INC-004',
            'date' => '2026-01-23',
            'description' => 'Test Transaction',
            'amount' => 100000,
            'business_unit_id' => $this->businessUnit->id,
            'category_id' => $this->incomeCategory->id,
            'status' => 'approved',
            'created_by' => $this->user->id,
            'approved_by' => $this->user->id,
            'approved_at' => now(),
        ]);

        $journal = $this->journalService->createFromIncome($incomeTransaction);

        $incomeTransaction->refresh();

        $this->assertEquals($journal->id, $incomeTransaction->journal_id);
    }

    #[Test]
    public function journal_has_correct_reference_type_and_id()
    {
        $incomeTransaction = IncomeTransaction::create([
            'transaction_number' => 'INC-005',
            'date' => '2026-01-23',
            'description' => 'Test Transaction',
            'amount' => 100000,
            'business_unit_id' => $this->businessUnit->id,
            'category_id' => $this->incomeCategory->id,
            'status' => 'approved',
            'created_by' => $this->user->id,
        ]);

        $journal = $this->journalService->createFromIncome($incomeTransaction);

        $this->assertEquals(IncomeTransaction::class, $journal->reference_type);
        $this->assertEquals($incomeTransaction->id, $journal->reference_id);
    }
}
