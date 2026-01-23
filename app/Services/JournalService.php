<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Models\ChartOfAccount;
use App\Models\ExpenseTransaction;
use App\Models\FiscalPeriod;
use App\Models\IncomeTransaction;
use App\Models\Journal;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;

class JournalService
{
    /**
     * Default cash account code
     */
    protected const CASH_ACCOUNT_CODE = '1110';

    /**
     * Get the default cash account
     */
    protected function getCashAccount(): ?ChartOfAccount
    {
        return ChartOfAccount::where('code', self::CASH_ACCOUNT_CODE)->first()
            ?? ChartOfAccount::where('type', 'asset')
                ->where('is_header', false)
                ->where('name', 'like', '%Kas%')
                ->first();
    }

    /**
     * Create journal from Income Transaction
     */
    public function createFromIncome(IncomeTransaction $transaction): ?Journal
    {
        $cashAccount = $this->getCashAccount();
        $revenueAccount = $transaction->category?->account;

        if (!$cashAccount || !$revenueAccount) {
            return null;
        }

        return DB::transaction(function () use ($transaction, $cashAccount, $revenueAccount) {
            $fiscalPeriod = FiscalPeriod::current();

            $journal = Journal::create([
                'journal_number' => Journal::generateNumber(),
                'date' => $transaction->date,
                'description' => "Pendapatan: {$transaction->description}",
                'business_unit_id' => $transaction->business_unit_id,
                'fiscal_period_id' => $fiscalPeriod?->id,
                'status' => 'approved',
                'type' => 'auto',
                'reference_type' => IncomeTransaction::class,
                'reference_id' => $transaction->id,
                'created_by' => $transaction->created_by,
                'approved_by' => $transaction->approved_by,
                'approved_at' => $transaction->approved_at,
            ]);

            // Debit: Kas (asset increases)
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $cashAccount->id,
                'description' => $transaction->description,
                'debit' => $transaction->amount,
                'credit' => 0,
            ]);

            // Credit: Pendapatan (revenue increases)
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $revenueAccount->id,
                'description' => $transaction->description,
                'debit' => 0,
                'credit' => $transaction->amount,
            ]);

            // Update transaction with journal reference
            $transaction->update(['journal_id' => $journal->id]);

            return $journal;
        });
    }

    /**
     * Create journal from Expense Transaction
     */
    public function createFromExpense(ExpenseTransaction $transaction): ?Journal
    {
        $cashAccount = $this->getCashAccount();
        $expenseAccount = $transaction->category?->account;

        if (!$cashAccount || !$expenseAccount) {
            return null;
        }

        return DB::transaction(function () use ($transaction, $cashAccount, $expenseAccount) {
            $fiscalPeriod = FiscalPeriod::current();

            $journal = Journal::create([
                'journal_number' => Journal::generateNumber(),
                'date' => $transaction->date,
                'description' => "Pengeluaran: {$transaction->description}",
                'business_unit_id' => $transaction->business_unit_id,
                'fiscal_period_id' => $fiscalPeriod?->id,
                'status' => 'approved',
                'type' => 'auto',
                'reference_type' => ExpenseTransaction::class,
                'reference_id' => $transaction->id,
                'created_by' => $transaction->created_by,
                'approved_by' => $transaction->approved_by,
                'approved_at' => $transaction->approved_at,
            ]);

            // Debit: Beban (expense increases)
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $expenseAccount->id,
                'description' => $transaction->description,
                'debit' => $transaction->amount,
                'credit' => 0,
            ]);

            // Credit: Kas (asset decreases)
            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $cashAccount->id,
                'description' => $transaction->description,
                'debit' => 0,
                'credit' => $transaction->amount,
            ]);

            // Update transaction with journal reference
            $transaction->update(['journal_id' => $journal->id]);

            return $journal;
        });
    }

    /**
     * Create journal from Cash Transaction
     */
    public function createFromCash(CashTransaction $transaction): ?Journal
    {
        $cashAccount = $this->getCashAccount();
        $categoryAccount = $transaction->category?->account;

        if (!$cashAccount) {
            return null;
        }

        // If no category account, use a default contra account
        // For cash in without category, we'll credit a suspense/other income account
        // For cash out without category, we'll debit a suspense/other expense account
        if (!$categoryAccount) {
            if ($transaction->type === 'in') {
                $categoryAccount = ChartOfAccount::where('type', 'revenue')
                    ->where('is_header', false)
                    ->first();
            } else {
                $categoryAccount = ChartOfAccount::where('type', 'expense')
                    ->where('is_header', false)
                    ->first();
            }
        }

        if (!$categoryAccount) {
            return null;
        }

        return DB::transaction(function () use ($transaction, $cashAccount, $categoryAccount) {
            $fiscalPeriod = FiscalPeriod::current();
            $typeLabel = $transaction->type === 'in' ? 'Kas Masuk' : 'Kas Keluar';

            $journal = Journal::create([
                'journal_number' => Journal::generateNumber(),
                'date' => $transaction->date,
                'description' => "{$typeLabel}: {$transaction->description}",
                'business_unit_id' => $transaction->business_unit_id,
                'fiscal_period_id' => $fiscalPeriod?->id,
                'status' => 'approved',
                'type' => 'auto',
                'reference_type' => CashTransaction::class,
                'reference_id' => $transaction->id,
                'created_by' => $transaction->created_by,
                'approved_by' => $transaction->approved_by,
                'approved_at' => $transaction->approved_at,
            ]);

            if ($transaction->type === 'in') {
                // Cash In: Debit Kas, Credit contra account
                JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $cashAccount->id,
                    'description' => $transaction->description,
                    'debit' => $transaction->amount,
                    'credit' => 0,
                ]);

                JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $categoryAccount->id,
                    'description' => $transaction->description,
                    'debit' => 0,
                    'credit' => $transaction->amount,
                ]);
            } else {
                // Cash Out: Debit contra account, Credit Kas
                JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $categoryAccount->id,
                    'description' => $transaction->description,
                    'debit' => $transaction->amount,
                    'credit' => 0,
                ]);

                JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $cashAccount->id,
                    'description' => $transaction->description,
                    'debit' => 0,
                    'credit' => $transaction->amount,
                ]);
            }

            // Update transaction with journal reference
            $transaction->update(['journal_id' => $journal->id]);

            return $journal;
        });
    }
}
