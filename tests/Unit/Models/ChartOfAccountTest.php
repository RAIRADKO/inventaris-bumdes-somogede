<?php

namespace Tests\Unit\Models;

use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\FiscalPeriod;
use App\Models\Journal;
use App\Models\JournalEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChartOfAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_chart_of_account()
    {
        $account = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('chart_of_accounts', [
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
        ]);
    }

    /** @test */
    public function it_can_have_parent_account()
    {
        $parentAccount = ChartOfAccount::create([
            'code' => '1000',
            'name' => 'Aset',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => true,
            'is_active' => true,
        ]);

        $childAccount = ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'parent_id' => $parentAccount->id,
            'is_header' => false,
            'is_active' => true,
        ]);

        $this->assertInstanceOf(ChartOfAccount::class, $childAccount->parent);
        $this->assertEquals('Aset', $childAccount->parent->name);
    }

    /** @test */
    public function it_can_have_children_accounts()
    {
        $parentAccount = ChartOfAccount::create([
            'code' => '1000',
            'name' => 'Aset',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => true,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'parent_id' => $parentAccount->id,
            'is_header' => false,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '1120',
            'name' => 'Bank',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'parent_id' => $parentAccount->id,
            'is_header' => false,
            'is_active' => true,
        ]);

        $this->assertCount(2, $parentAccount->children);
    }

    /** @test */
    public function active_scope_filters_active_accounts()
    {
        ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '1120',
            'name' => 'Bank (Inactive)',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => false,
        ]);

        $activeAccounts = ChartOfAccount::active()->get();

        $this->assertCount(1, $activeAccounts);
        $this->assertEquals('Kas', $activeAccounts->first()->name);
    }

    /** @test */
    public function postable_scope_filters_non_header_active_accounts()
    {
        ChartOfAccount::create([
            'code' => '1000',
            'name' => 'Aset',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => true,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '1110',
            'name' => 'Kas',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => true,
        ]);

        ChartOfAccount::create([
            'code' => '1120',
            'name' => 'Bank (Inactive)',
            'type' => 'asset',
            'normal_balance' => 'debit',
            'is_header' => false,
            'is_active' => false,
        ]);

        $postableAccounts = ChartOfAccount::postable()->get();

        $this->assertCount(1, $postableAccounts);
        $this->assertEquals('Kas', $postableAccounts->first()->name);
    }

    /** @test */
    public function it_calculates_balance_for_debit_normal_account()
    {
        $businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Unit',
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

        $revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $journal = Journal::create([
            'journal_number' => 'JRN-001',
            'date' => now(),
            'description' => 'Test Entry',
            'business_unit_id' => $businessUnit->id,
            'status' => 'approved',
            'type' => 'manual',
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'description' => 'Debit Kas',
            'debit' => 1000000,
            'credit' => 0,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $revenueAccount->id,
            'description' => 'Credit Pendapatan',
            'debit' => 0,
            'credit' => 1000000,
        ]);

        // For debit normal balance: debit - credit
        $this->assertEquals(1000000, $cashAccount->getBalance());
    }

    /** @test */
    public function it_calculates_balance_for_credit_normal_account()
    {
        $businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Unit',
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

        $revenueAccount = ChartOfAccount::create([
            'code' => '4110',
            'name' => 'Pendapatan',
            'type' => 'revenue',
            'normal_balance' => 'credit',
            'is_header' => false,
            'is_active' => true,
        ]);

        $journal = Journal::create([
            'journal_number' => 'JRN-001',
            'date' => now(),
            'description' => 'Test Entry',
            'business_unit_id' => $businessUnit->id,
            'status' => 'approved',
            'type' => 'manual',
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $cashAccount->id,
            'description' => 'Debit Kas',
            'debit' => 500000,
            'credit' => 0,
        ]);

        JournalEntry::create([
            'journal_id' => $journal->id,
            'account_id' => $revenueAccount->id,
            'description' => 'Credit Pendapatan',
            'debit' => 0,
            'credit' => 500000,
        ]);

        // For credit normal balance: credit - debit
        $this->assertEquals(500000, $revenueAccount->getBalance());
    }

    /** @test */
    public function balance_only_includes_approved_journals()
    {
        $businessUnit = BusinessUnit::create([
            'code' => 'BU001',
            'name' => 'Test Unit',
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

        // Approved journal
        $approvedJournal = Journal::create([
            'journal_number' => 'JRN-001',
            'date' => now(),
            'description' => 'Approved',
            'business_unit_id' => $businessUnit->id,
            'status' => 'approved',
            'type' => 'manual',
        ]);

        JournalEntry::create([
            'journal_id' => $approvedJournal->id,
            'account_id' => $cashAccount->id,
            'debit' => 1000000,
            'credit' => 0,
        ]);

        // Draft journal (should not be included)
        $draftJournal = Journal::create([
            'journal_number' => 'JRN-002',
            'date' => now(),
            'description' => 'Draft',
            'business_unit_id' => $businessUnit->id,
            'status' => 'draft',
            'type' => 'manual',
        ]);

        JournalEntry::create([
            'journal_id' => $draftJournal->id,
            'account_id' => $cashAccount->id,
            'debit' => 500000,
            'credit' => 0,
        ]);

        // Only approved journal should be counted
        $this->assertEquals(1000000, $cashAccount->getBalance());
    }
}
