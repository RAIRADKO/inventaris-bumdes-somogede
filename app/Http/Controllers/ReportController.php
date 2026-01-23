<?php

namespace App\Http\Controllers;

use App\Exports\BalanceSheetExport;
use App\Exports\IncomeStatementExport;
use App\Exports\TrialBalanceExport;
use App\Models\ChartOfAccount;
use App\Models\ExpenseTransaction;
use App\Models\IncomeTransaction;
use App\Models\Journal;
use App\Models\JournalEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('report.index');
    }

    // Laporan Laba Rugi
    public function incomeStatement(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Revenue accounts
        $revenues = ChartOfAccount::where('type', 'revenue')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) use ($startDate, $endDate) {
                $account->balance = $account->getBalance($startDate, $endDate);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $totalRevenue = $revenues->sum('balance');

        // Expense accounts
        $expenses = ChartOfAccount::where('type', 'expense')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) use ($startDate, $endDate) {
                $account->balance = $account->getBalance($startDate, $endDate);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $totalExpense = $expenses->sum('balance');

        $netIncome = $totalRevenue - $totalExpense;

        return view('report.income-statement', compact(
            'startDate', 'endDate', 'revenues', 'expenses',
            'totalRevenue', 'totalExpense', 'netIncome'
        ));
    }

    // Laporan Neraca
    public function balanceSheet(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        // Assets
        $assets = ChartOfAccount::where('type', 'asset')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) use ($date) {
                $account->balance = $account->getBalance(null, $date);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $totalAssets = $assets->sum('balance');

        // Liabilities
        $liabilities = ChartOfAccount::where('type', 'liability')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) use ($date) {
                $account->balance = $account->getBalance(null, $date);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $totalLiabilities = $liabilities->sum('balance');

        // Equity
        $equities = ChartOfAccount::where('type', 'equity')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) use ($date) {
                $account->balance = $account->getBalance(null, $date);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $totalEquity = $equities->sum('balance');

        return view('report.balance-sheet', compact(
            'date', 'assets', 'liabilities', 'equities',
            'totalAssets', 'totalLiabilities', 'totalEquity'
        ));
    }

    // Laporan Arus Kas
    public function cashFlow(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Operating activities - from approved income and expense transactions
        $operatingIncome = IncomeTransaction::approved()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $operatingExpense = ExpenseTransaction::approved()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        $netOperating = $operatingIncome - $operatingExpense;

        // Investment activities - from asset acquisitions and disposals
        $assetPurchases = \App\Models\Asset::whereBetween('acquisition_date', [$startDate, $endDate])
            ->sum('acquisition_cost');
        
        $assetSales = \App\Models\Asset::whereBetween('disposal_date', [$startDate, $endDate])
            ->whereNotNull('disposal_value')
            ->sum('disposal_value');
        
        $investingActivities = $assetSales - $assetPurchases;

        // Financing activities - from capital records
        $capitalIn = \App\Models\CapitalRecord::whereIn('type', ['initial_capital', 'village_investment', 'community_investment'])
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
        
        $capitalOut = \App\Models\CapitalRecord::whereIn('type', ['dividend_distribution'])
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
        
        $financingActivities = $capitalIn - $capitalOut;

        $netCashFlow = $netOperating + $investingActivities + $financingActivities;

        return view('report.cash-flow', compact(
            'startDate', 'endDate',
            'operatingIncome', 'operatingExpense', 'netOperating',
            'investingActivities', 'financingActivities', 'netCashFlow',
            'assetPurchases', 'assetSales', 'capitalIn', 'capitalOut'
        ));
    }

    // Buku Besar
    public function generalLedger(Request $request)
    {
        $accountId = $request->get('account_id');
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $accounts = ChartOfAccount::where('is_header', false)->orderBy('code')->get();
        $entries = collect();
        $account = null;

        if ($accountId) {
            $account = ChartOfAccount::find($accountId);
            
            $entries = JournalEntry::with('journal')
                ->where('account_id', $accountId)
                ->whereHas('journal', function ($q) use ($startDate, $endDate) {
                    $q->where('status', 'approved')
                      ->whereBetween('date', [$startDate, $endDate]);
                })
                ->orderBy('created_at')
                ->get();
        }

        return view('report.general-ledger', compact('accounts', 'account', 'entries', 'startDate', 'endDate'));
    }

    // Trial Balance
    public function trialBalance(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $accounts = ChartOfAccount::where('is_header', false)
            ->orderBy('code')
            ->get()
            ->map(function ($account) use ($date) {
                $balance = $account->getBalance(null, $date);
                $account->debit_balance = $account->normal_balance === 'debit' ? $balance : 0;
                $account->credit_balance = $account->normal_balance === 'credit' ? $balance : 0;
                return $account;
            })
            ->filter(fn($a) => $a->debit_balance != 0 || $a->credit_balance != 0);

        $totalDebit = $accounts->sum('debit_balance');
        $totalCredit = $accounts->sum('credit_balance');

        return view('report.trial-balance', compact('date', 'accounts', 'totalDebit', 'totalCredit'));
    }

    // Export Excel - Income Statement
    public function exportIncomeStatementExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        $filename = 'laporan-laba-rugi-' . $startDate . '-' . $endDate . '.xlsx';
        return Excel::download(new IncomeStatementExport($startDate, $endDate), $filename);
    }

    // Export PDF - Income Statement
    public function exportIncomeStatementPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $revenues = ChartOfAccount::where('type', 'revenue')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) use ($startDate, $endDate) {
                $account->balance = $account->getBalance($startDate, $endDate);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $expenses = ChartOfAccount::where('type', 'expense')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) use ($startDate, $endDate) {
                $account->balance = $account->getBalance($startDate, $endDate);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'revenues' => $revenues,
            'expenses' => $expenses,
            'totalRevenue' => $revenues->sum('balance'),
            'totalExpense' => $expenses->sum('balance'),
            'netIncome' => $revenues->sum('balance') - $expenses->sum('balance'),
        ];

        $pdf = Pdf::loadView('report.pdf.income-statement', $data);
        return $pdf->download('laporan-laba-rugi-' . $startDate . '-' . $endDate . '.pdf');
    }

    // Export Excel - Balance Sheet
    public function exportBalanceSheetExcel(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        return Excel::download(new BalanceSheetExport($date), 'neraca-' . $date . '.xlsx');
    }

    // Export PDF - Balance Sheet
    public function exportBalanceSheetPdf(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $assets = ChartOfAccount::where('type', 'asset')->where('is_header', false)->get()
            ->map(fn($a) => tap($a, fn($a) => $a->balance = $a->getBalance(null, $date)))
            ->filter(fn($a) => $a->balance != 0);

        $liabilities = ChartOfAccount::where('type', 'liability')->where('is_header', false)->get()
            ->map(fn($a) => tap($a, fn($a) => $a->balance = $a->getBalance(null, $date)))
            ->filter(fn($a) => $a->balance != 0);

        $equities = ChartOfAccount::where('type', 'equity')->where('is_header', false)->get()
            ->map(fn($a) => tap($a, fn($a) => $a->balance = $a->getBalance(null, $date)))
            ->filter(fn($a) => $a->balance != 0);

        $data = [
            'date' => $date,
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equities' => $equities,
            'totalAssets' => $assets->sum('balance'),
            'totalLiabilities' => $liabilities->sum('balance'),
            'totalEquity' => $equities->sum('balance'),
        ];

        $pdf = Pdf::loadView('report.pdf.balance-sheet', $data);
        return $pdf->download('neraca-' . $date . '.pdf');
    }

    // Export Excel - Trial Balance
    public function exportTrialBalanceExcel(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        return Excel::download(new TrialBalanceExport($date), 'neraca-saldo-' . $date . '.xlsx');
    }

    // Export PDF - Trial Balance
    public function exportTrialBalancePdf(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));

        $accounts = ChartOfAccount::where('is_header', false)->orderBy('code')->get()
            ->map(function ($account) use ($date) {
                $balance = $account->getBalance(null, $date);
                $account->debit_balance = $account->normal_balance === 'debit' ? $balance : 0;
                $account->credit_balance = $account->normal_balance === 'credit' ? $balance : 0;
                return $account;
            })
            ->filter(fn($a) => $a->debit_balance != 0 || $a->credit_balance != 0);

        $data = [
            'date' => $date,
            'accounts' => $accounts,
            'totalDebit' => $accounts->sum('debit_balance'),
            'totalCredit' => $accounts->sum('credit_balance'),
        ];

        $pdf = Pdf::loadView('report.pdf.trial-balance', $data);
        return $pdf->download('neraca-saldo-' . $date . '.pdf');
    }
}
