<?php

namespace App\Exports;

use App\Models\ChartOfAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IncomeStatementExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;
    protected $data;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->prepareData();
    }

    protected function prepareData()
    {
        $revenues = ChartOfAccount::where('type', 'revenue')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) {
                $account->balance = $account->getBalance($this->startDate, $this->endDate);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $expenses = ChartOfAccount::where('type', 'expense')
            ->where('is_header', false)
            ->get()
            ->map(function ($account) {
                $account->balance = $account->getBalance($this->startDate, $this->endDate);
                return $account;
            })
            ->filter(fn($a) => $a->balance != 0);

        $this->data = [
            'revenues' => $revenues,
            'expenses' => $expenses,
            'totalRevenue' => $revenues->sum('balance'),
            'totalExpense' => $expenses->sum('balance'),
        ];
    }

    public function collection()
    {
        $rows = collect();

        // Header
        $rows->push(['BUMDES SOMOGEDE']);
        $rows->push(['Laporan Laba Rugi']);
        $rows->push(['Periode: ' . \Carbon\Carbon::parse($this->startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($this->endDate)->format('d M Y')]);
        $rows->push(['']);

        // Pendapatan
        $rows->push(['PENDAPATAN', '', '']);
        foreach ($this->data['revenues'] as $account) {
            $rows->push([$account->code, $account->name, $account->balance]);
        }
        $rows->push(['', 'Total Pendapatan', $this->data['totalRevenue']]);
        $rows->push(['']);

        // Beban
        $rows->push(['BEBAN', '', '']);
        foreach ($this->data['expenses'] as $account) {
            $rows->push([$account->code, $account->name, $account->balance]);
        }
        $rows->push(['', 'Total Beban', $this->data['totalExpense']]);
        $rows->push(['']);

        // Laba Rugi
        $netIncome = $this->data['totalRevenue'] - $this->data['totalExpense'];
        $rows->push(['', 'LABA/RUGI BERSIH', $netIncome]);

        return $rows;
    }

    public function headings(): array
    {
        return ['Kode', 'Nama Akun', 'Jumlah'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Laba Rugi';
    }
}
