<?php

namespace Tests\Unit\Models;

use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\FiscalPeriod;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JournalTest extends TestCase
{
    use RefreshDatabase;

    protected BusinessUnit $businessUnit;
    protected FiscalPeriod $fiscalPeriod;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Business Unit',
            'is_active' => true,
        ]);

        $this->fiscalPeriod = FiscalPeriod::create([
            'name' => 'Tahun 2026',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_active' => true,
            'is_closed' => false,
        ]);

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'director',
        ]);
    }

    /** @test */
    public function it_can_create_a_journal()
    {
        $journal = Journal::create([
            'journal_number' => 'JRN-20260123-0001',
            'date' => '2026-01-23',
            'description' => 'Test Journal Entry',
            'business_unit_id' => $this->businessUnit->id,
            'fiscal_period_id' => $this->fiscalPeriod->id,
            'status' => 'draft',
            'type' => 'manual',
            'created_by' => $this->user->id,
        ]);

        $this->assertDatabaseHas('journals', [
            'journal_number' => 'JRN-20260123-0001',
            'description' => 'Test Journal Entry',
        ]);
    }

    /** @test */
    public function it_generates_unique_journal_numbers()
    {
        $number1 = Journal::generateNumber();
        
        Journal::create([
            'journal_number' => $number1,
            'date' => now(),
            'description' => 'First Journal',
            'business_unit_id' => $this->businessUnit->id,
            'status' => 'draft',
            'type' => 'manual',
        ]);

        $number2 = Journal::generateNumber();

        $this->assertNotEquals($number1, $number2);
        $this->assertStringStartsWith('JRN-', $number1);
        $this->assertStringStartsWith('JRN-', $number2);
    }

    /** @test */
    public function journal_belongs_to_business_unit()
    {
        $journal = Journal::create([
            'journal_number' => 'JRN-20260123-0001',
            'date' => '2026-01-23',
            'description' => 'Test Journal',
            'business_unit_id' => $this->businessUnit->id,
            'status' => 'draft',
            'type' => 'manual',
        ]);

        $this->assertInstanceOf(BusinessUnit::class, $journal->businessUnit);
        $this->assertEquals('Test Business Unit', $journal->businessUnit->name);
    }

    /** @test */
    public function journal_belongs_to_fiscal_period()
    {
        $journal = Journal::create([
            'journal_number' => 'JRN-20260123-0001',
            'date' => '2026-01-23',
            'description' => 'Test Journal',
            'business_unit_id' => $this->businessUnit->id,
            'fiscal_period_id' => $this->fiscalPeriod->id,
            'status' => 'draft',
            'type' => 'manual',
        ]);

        $this->assertInstanceOf(FiscalPeriod::class, $journal->fiscalPeriod);
        $this->assertEquals('Tahun 2026', $journal->fiscalPeriod->name);
    }

    /** @test */
    public function journal_has_many_entries()
    {
        $cashAccount = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan Usaha',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $journal = Journal::create([
            'journal_number' => 'JRN-20260123-0001',
            'date' => '2026-01-23',
            'description' => 'Test Journal',
            'business_unit_id' => $this->businessUnit->id,
            'status' => 'draft',
            'type' => 'manual',
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'description' => 'Debit entry',
            'debit' => 1000000,
            'credit' => 0,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $revenueAccount->id,
            'description' => 'Credit entry',
            'debit' => 0,
            'credit' => 1000000,
        ]);

        $this->assertCount(2, $journal->entries);
    }

    /** @test */
    public function balanced_journal_returns_true()
    {
        $cashAccount = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan Usaha',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $journal = Journal::create([
            'journal_number' => 'JRN-20260123-0001',
            'date' => '2026-01-23',
            'description' => 'Balanced Journal',
            'business_unit_id' => $this->businessUnit->id,
            'status' => 'draft',
            'type' => 'manual',
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'description' => 'Debit',
            'debit' => 500000,
            'credit' => 0,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $revenueAccount->id,
            'description' => 'Credit',
            'debit' => 0,
            'credit' => 500000,
        ]);

        $this->assertTrue($journal->isBalanced());
    }

    /** @test */
    public function unbalanced_journal_returns_false()
    {
        $cashAccount = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan Usaha',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $journal = Journal::create([
            'journal_number' => 'JRN-20260123-0002',
            'date' => '2026-01-23',
            'description' => 'Unbalanced Journal',
            'business_unit_id' => $this->businessUnit->id,
            'status' => 'draft',
            'type' => 'manual',
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'description' => 'Debit',
            'debit' => 500000,
            'credit' => 0,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $revenueAccount->id,
            'description' => 'Credit',
            'debit' => 0,
            'credit' => 300000, // Different amount
        ]);

        $this->assertFalse($journal->isBalanced());
    }

    /** @test */
    public function it_calculates_total_debit_attribute()
    {
        $cashAccount = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $journal = Journal::create([
            'journal_number' => 'JRN-20260123-0001',
            'date' => '2026-01-23',
            'description' => 'Test Journal',
            'business_unit_id' => $this->businessUnit->id,
            'status' => 'draft',
            'type' => 'manual',
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'description' => 'Entry 1',
            'debit' => 100000,
            'credit' => 0,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'description' => 'Entry 2',
            'debit' => 200000,
            'credit' => 0,
        ]);

        $this->assertEquals(300000, $journal->total_debit);
    }

    /** @test */
    public function it_calculates_total_credit_attribute()
    {
        $revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan Usaha',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $journal = Journal::create([
            'journal_number' => 'JRN-20260123-0001',
            'date' => '2026-01-23',
            'description' => 'Test Journal',
            'business_unit_id' => $this->businessUnit->id,
            'status' => 'draft',
            'type' => 'manual',
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $revenueAccount->id,
            'description' => 'Entry 1',
            'debit' => 0,
            'credit' => 150000,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $revenueAccount->id,
            'description' => 'Entry 2',
            'debit' => 0,
            'credit' => 250000,
        ]);

        $this->assertEquals(400000, $journal->total_credit);
    }

    /** @test */
    public function journal_belongs_to_created_by_user()
    {
        $journal = Journal::create([
            'journal_number' => 'JRN-20260123-0001',
            'date' => '2026-01-23',
            'description' => 'Test Journal',
            'business_unit_id' => $this->businessUnit->id,
            'status' => 'draft',
            'type' => 'manual',
            'created_by' => $this->user->id,
        ]);

        $this->assertInstanceOf(User::class, $journal->createdBy);
        $this->assertEquals('Test User', $journal->createdBy->name);
    }
}
