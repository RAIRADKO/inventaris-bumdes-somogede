<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Budget;
use App\Models\CashTransaction;
use App\Models\ExpenseTransaction;
use App\Models\IncomeTransaction;
use App\Models\Payable;
use App\Models\Receivable;
use App\Models\TaxRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cash balance
        $cashIn = CashTransaction::approved()->cashIn()->sum('amount');
        $cashOut = CashTransaction::approved()->cashOut()->sum('amount');
        $cashBalance = $cashIn - $cashOut;

        // Current month stats
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $monthlyIncome = IncomeTransaction::approved()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthlyExpense = ExpenseTransaction::approved()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $profitLoss = $monthlyIncome - $monthlyExpense;

        // Receivables & Payables
        $totalReceivables = Receivable::unpaid()->sum('remaining_amount');
        $overdueReceivables = Receivable::overdue()->sum('remaining_amount');
        $totalPayables = Payable::unpaid()->sum('remaining_amount');

        // Assets value
        $totalAssetValue = Asset::active()->sum('current_value');

        // Monthly trend (last 6 months)
        $monthlyTrend = $this->getMonthlyTrend(6);

        // Recent transactions
        $recentTransactions = $this->getRecentTransactions(10);

        // Pending approvals
        $pendingApprovals = $this->getPendingApprovals();

        // Upcoming due dates
        $upcomingDueDates = $this->getUpcomingDueDates();

        // Tax reminders
        $pendingTaxes = TaxRecord::pending()
            ->where('due_date', '<=', now()->addDays(30))
            ->orderBy('due_date')
            ->get();

        return view('dashboard.index', compact(
            'cashBalance',
            'monthlyIncome',
            'monthlyExpense',
            'profitLoss',
            'totalReceivables',
            'overdueReceivables',
            'totalPayables',
            'totalAssetValue',
            'monthlyTrend',
            'recentTransactions',
            'pendingApprovals',
            'upcomingDueDates',
            'pendingTaxes'
        ));
    }

    private function getMonthlyTrend(int $months): array
    {
        $trend = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $income = IncomeTransaction::approved()
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $expense = ExpenseTransaction::approved()
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $trend[] = [
                'month' => $date->format('M Y'),
                'income' => (float) $income,
                'expense' => (float) $expense,
                'profit' => (float) ($income - $expense),
            ];
        }

        return $trend;
    }

    private function getRecentTransactions(int $limit): array
    {
        $income = IncomeTransaction::with('category')
            ->latest('date')
            ->take($limit)
            ->get()
            ->map(fn($t) => [
                'type' => 'income',
                'date' => $t->date,
                'description' => $t->description,
                'category' => $t->category?->name,
                'amount' => $t->amount,
                'status' => $t->status,
            ]);

        $expense = ExpenseTransaction::with('category')
            ->latest('date')
            ->take($limit)
            ->get()
            ->map(fn($t) => [
                'type' => 'expense',
                'date' => $t->date,
                'description' => $t->description,
                'category' => $t->category?->name,
                'amount' => $t->amount,
                'status' => $t->status,
            ]);

        return $income->merge($expense)
            ->sortByDesc('date')
            ->take($limit)
            ->values()
            ->toArray();
    }

    private function getPendingApprovals(): array
    {
        $pendingIncome = IncomeTransaction::where('status', 'pending')->count();
        $pendingExpense = ExpenseTransaction::where('status', 'pending')->count();
        $pendingCash = CashTransaction::where('status', 'pending')->count();

        return [
            'income' => $pendingIncome,
            'expense' => $pendingExpense,
            'cash' => $pendingCash,
            'total' => $pendingIncome + $pendingExpense + $pendingCash,
        ];
    }

    private function getUpcomingDueDates(): array
    {
        $receivables = Receivable::unpaid()
            ->where('due_date', '<=', now()->addDays(7))
            ->with('customer')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        $payables = Payable::unpaid()
            ->where('due_date', '<=', now()->addDays(7))
            ->with('supplier')
            ->orderBy('due_date')
            ->take(5)
            ->get();

        return [
            'receivables' => $receivables,
            'payables' => $payables,
        ];
    }

    public function statistics()
    {
        // Financial ratios
        $totalAssets = Asset::active()->sum('current_value');
        $totalLiabilities = Payable::unpaid()->sum('remaining_amount');
        $equity = $totalAssets - $totalLiabilities;

        // Current ratio (Current Assets / Current Liabilities)
        $currentAssets = CashTransaction::approved()->cashIn()->sum('amount') 
                       - CashTransaction::approved()->cashOut()->sum('amount')
                       + Receivable::unpaid()->sum('remaining_amount');
        $currentLiabilities = Payable::unpaid()->sum('remaining_amount');
        $currentRatio = $currentLiabilities > 0 ? $currentAssets / $currentLiabilities : 0;

        // Year to date
        $startOfYear = now()->startOfYear();
        $ytdIncome = IncomeTransaction::approved()
            ->where('date', '>=', $startOfYear)
            ->sum('amount');
        $ytdExpense = ExpenseTransaction::approved()
            ->where('date', '>=', $startOfYear)
            ->sum('amount');
        $ytdProfit = $ytdIncome - $ytdExpense;

        // Profit margin
        $profitMargin = $ytdIncome > 0 ? ($ytdProfit / $ytdIncome) * 100 : 0;

        return view('dashboard.statistics', compact(
            'totalAssets',
            'totalLiabilities',
            'equity',
            'currentRatio',
            'ytdIncome',
            'ytdExpense',
            'ytdProfit',
            'profitMargin'
        ));
    }
}
