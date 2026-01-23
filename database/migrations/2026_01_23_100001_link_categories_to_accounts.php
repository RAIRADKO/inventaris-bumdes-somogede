<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Map category names to account codes
        $categoryAccountMap = [
            // Income categories
            'Pendapatan Usaha' => '4110', // Pendapatan Simpan Pinjam or general
            'Pendapatan Bunga' => '4210', 
            'Pendapatan Hibah' => '4220',
            'Pendapatan Lain-lain' => '4200',
            
            // Expense categories
            'Gaji & Tunjangan' => '5110',
            'Pembelian Bahan Baku' => '5160', // Beban Perlengkapan
            'Biaya Utilitas' => '5120', // Beban Listrik
            'Biaya Transportasi' => '5150',
            'Biaya Perawatan' => '5180',
            'Biaya Administrasi' => '5170',
            'Pajak & Retribusi' => '5300', // Beban Pajak
            'Biaya Lain-lain' => '5400', // Beban Lain-lain
        ];

        foreach ($categoryAccountMap as $categoryName => $accountCode) {
            $account = DB::table('chart_of_accounts')->where('code', $accountCode)->first();
            if ($account) {
                DB::table('transaction_categories')
                    ->where('name', $categoryName)
                    ->update(['account_id' => $account->id]);
            }
        }
    }

    public function down(): void
    {
        DB::table('transaction_categories')->update(['account_id' => null]);
    }
};
