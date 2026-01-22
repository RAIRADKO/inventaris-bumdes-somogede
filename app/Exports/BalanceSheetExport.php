<?php

namespace App\Exports;

use App\Models\ChartOfAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BalanceSheetExport implements FromCollection, WithStyles, WithTitle, ShouldAutoSize
{
    protected $date;
    protected $data;

    public function __construct($date)
    {
        $this->date = $date;
        $this->prepareData();
    }

    protected function prepareData()
    {
        $assets = ChartOfAccount::where('type', 'asset')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) {
                $account->balance = $account->getBalance(null, $this->date);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $liabilities = ChartOfAccount::where('type', 'liability')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) {
                $account->balance = $account->getBalance(null, $this->date);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $equities = ChartOfAccount::where('type', 'equity')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) {
                $account->balance = $account->getBalance(null, $this->date);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $this->data = [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equities' => $equities,
            'totalAssets' => $assets->sum('balance'),
            'totalLiabilities' => $liabilities->sum('balance'),
            'totalEquity' => $equities->sum('balance'),
        ];
    }

    public function collection()
    {
        $rows = collect();

        // Header
        $rows->push(['BUMDES SOMOGEDE', '', '']);
        $rows->push(['Laporan Neraca', '', '']);
        $rows->push(['Per Tanggal: ' . \Carbon\Carbon::parse($this->date)->format('d M Y'), '', '']);
        $rows->push(['', '', '']);

        // Aset
        $rows->push(['ASET', '', '']);
        foreach ($this->data['assets'] as $account) {
            $rows->push([$account->code, $account->name, $account->balance]);
        }
        $rows->push(['', 'Total Aset', $this->data['totalAssets']]);
        $rows->push(['', '', '']);

        // Kewajiban
        $rows->push(['KEWAJIBAN', '', '']);
        foreach ($this->data['liabilities'] as $account) {
            $rows->push([$account->code, $account->name, $account->balance]);
        }
        $rows->push(['', 'Total Kewajiban', $this->data['totalLiabilities']]);
        $rows->push(['', '', '']);

        // Ekuitas
        $rows->push(['EKUITAS', '', '']);
        foreach ($this->data['equities'] as $account) {
            $rows->push([$account->code, $account->name, $account->balance]);
        }
        $rows->push(['', 'Total Ekuitas', $this->data['totalEquity']]);
        $rows->push(['', '', '']);

        $rows->push(['', 'KEWAJIBAN + EKUITAS', $this->data['totalLiabilities'] + $this->data['totalEquity']]);

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }

    public function title(): string
    {
        return 'Neraca';
    }
}
