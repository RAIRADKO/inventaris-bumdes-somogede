<?php

namespace App\Console\Commands;

use App\Models\CashTransaction;
use App\Models\ExpenseTransaction;
use App\Models\IncomeTransaction;
use App\Services\JournalService;
use Illuminate\Console\Command;

class SyncTransactionJournals extends Command
{
    protected $signature = 'transactions:sync-journals';
    protected $description = 'Create journals for approved transactions that do not have journals';

    public function handle(): int
    {
        $journalService = new JournalService();
        $created = 0;
        $failed = 0;

        // Sync Income Transactions
        $incomeTransactions = IncomeTransaction::approved()
            ->whereNull('journal_id')
            ->get();

        foreach ($incomeTransactions as $transaction) {
            $journal = $journalService->createFromIncome($transaction);
            if ($journal) {
                $this->info("Created journal for Income #{$transaction->transaction_number}");
                $created++;
            } else {
                $this->warn("Failed to create journal for Income #{$transaction->transaction_number}");
                $failed++;
            }
        }

        // Sync Expense Transactions
        $expenseTransactions = ExpenseTransaction::approved()
            ->whereNull('journal_id')
            ->get();

        foreach ($expenseTransactions as $transaction) {
            $journal = $journalService->createFromExpense($transaction);
            if ($journal) {
                $this->info("Created journal for Expense #{$transaction->transaction_number}");
                $created++;
            } else {
                $this->warn("Failed to create journal for Expense #{$transaction->transaction_number}");
                $failed++;
            }
        }

        // Sync Cash Transactions
        $cashTransactions = CashTransaction::approved()
            ->whereNull('journal_id')
            ->get();

        foreach ($cashTransactions as $transaction) {
            $journal = $journalService->createFromCash($transaction);
            if ($journal) {
                $this->info("Created journal for Cash #{$transaction->transaction_number}");
                $created++;
            } else {
                $this->warn("Failed to create journal for Cash #{$transaction->transaction_number}");
                $failed++;
            }
        }

        $this->info("Sync completed. Created: {$created}, Failed: {$failed}");

        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
