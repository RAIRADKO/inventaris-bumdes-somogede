<?php

namespace App\Exports;

use App\Models\ChartOfAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrialBalanceExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
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
        $this->data = ChartOfAccount::where('is_header', false)
            ->orderBy('code')
            ->get()
            ->map(function ($account) {
                $balance = $account->getBalance(null, $this->date);
                $account->debit_balance = $account->normal_balance === 'debit' ? $balance : 0;
                $account->credit_balance = $account->normal_balance === 'credit' ? $balance : 0;
                return $account;
            })
            ->filter(fn($a) => $a->debit_balance != 0 || $a->credit_balance != 0);
    }

    public function collection()
    {
        $rows = collect();

        // Header
        $rows->push(['BUMDES SOMOGEDE', '', '', '']);
        $rows->push(['Neraca Saldo', '', '', '']);
        $rows->push(['Per Tanggal: ' . \Carbon\Carbon::parse($this->date)->format('d M Y'), '', '', '']);
        $rows->push(['', '', '', '']);
        $rows->push(['Kode', 'Nama Akun', 'Debit', 'Kredit']);

        foreach ($this->data as $account) {
            $rows->push([
                $account->code,
                $account->name,
                $account->debit_balance > 0 ? $account->debit_balance : '',
                $account->credit_balance > 0 ? $account->credit_balance : '',
            ]);
        }

        $rows->push(['']);
        $rows->push(['', 'TOTAL', $this->data->sum('debit_balance'), $this->data->sum('credit_balance')]);

        return $rows;
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            5 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Neraca Saldo';
    }
}
