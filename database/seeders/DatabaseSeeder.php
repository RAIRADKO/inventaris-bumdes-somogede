<?php

namespace Database\Seeders;

use App\Models\BusinessUnit;
use App\Models\ChartOfAccount;
use App\Models\FiscalPeriod;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@bumdes-somogede.id',
            'password' => Hash::make('password'),
            'role' => 'director',
            'is_active' => true,
        ]);

        // Create fiscal period
        FiscalPeriod::create([
            'name' => 'Tahun Anggaran 2026',
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'is_active' => true,
        ]);

        // Create business units
        $units = [
            ['code' => 'UNIT-01', 'name' => 'Unit Simpan Pinjam', 'description' => 'Unit usaha simpan pinjam masyarakat'],
            ['code' => 'UNIT-02', 'name' => 'Unit Perdagangan', 'description' => 'Unit usaha perdagangan umum'],
            ['code' => 'UNIT-03', 'name' => 'Unit Pertanian', 'description' => 'Unit usaha pertanian dan peternakan'],
        ];
        foreach ($units as $unit) {
            BusinessUnit::create($unit);
        }

        // Create Chart of Accounts (COA)
        $this->seedChartOfAccounts();

        // Create Transaction Categories
        $this->seedTransactionCategories();

        // Seed demo data
        $this->call(DemoSeeder::class);
    }

    private function seedChartOfAccounts(): void
    {
        $accounts = [
            // ASET (1xxx)
            ['code' => '1000', 'name' => 'ASET', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 1, 'is_header' => true],
            ['code' => '1100', 'name' => 'Aset Lancar', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 2, 'is_header' => true, 'parent_code' => '1000'],
            ['code' => '1110', 'name' => 'Kas', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '1100'],
            ['code' => '1111', 'name' => 'Kas Kecil', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 4, 'parent_code' => '1110'],
            ['code' => '1112', 'name' => 'Kas di Bank', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 4, 'parent_code' => '1110'],
            ['code' => '1120', 'name' => 'Piutang Usaha', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '1100'],
            ['code' => '1130', 'name' => 'Persediaan', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '1100'],
            ['code' => '1200', 'name' => 'Aset Tetap', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 2, 'is_header' => true, 'parent_code' => '1000'],
            ['code' => '1210', 'name' => 'Tanah', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '1200'],
            ['code' => '1220', 'name' => 'Bangunan', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '1200'],
            ['code' => '1221', 'name' => 'Akumulasi Penyusutan Bangunan', 'type' => 'asset', 'normal_balance' => 'credit', 'level' => 4, 'parent_code' => '1220'],
            ['code' => '1230', 'name' => 'Kendaraan', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '1200'],
            ['code' => '1231', 'name' => 'Akumulasi Penyusutan Kendaraan', 'type' => 'asset', 'normal_balance' => 'credit', 'level' => 4, 'parent_code' => '1230'],
            ['code' => '1240', 'name' => 'Peralatan', 'type' => 'asset', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '1200'],
            ['code' => '1241', 'name' => 'Akumulasi Penyusutan Peralatan', 'type' => 'asset', 'normal_balance' => 'credit', 'level' => 4, 'parent_code' => '1240'],

            // KEWAJIBAN (2xxx)
            ['code' => '2000', 'name' => 'KEWAJIBAN', 'type' => 'liability', 'normal_balance' => 'credit', 'level' => 1, 'is_header' => true],
            ['code' => '2100', 'name' => 'Kewajiban Jangka Pendek', 'type' => 'liability', 'normal_balance' => 'credit', 'level' => 2, 'is_header' => true, 'parent_code' => '2000'],
            ['code' => '2110', 'name' => 'Hutang Usaha', 'type' => 'liability', 'normal_balance' => 'credit', 'level' => 3, 'parent_code' => '2100'],
            ['code' => '2120', 'name' => 'Hutang Pajak', 'type' => 'liability', 'normal_balance' => 'credit', 'level' => 3, 'parent_code' => '2100'],
            ['code' => '2130', 'name' => 'Hutang Gaji', 'type' => 'liability', 'normal_balance' => 'credit', 'level' => 3, 'parent_code' => '2100'],
            ['code' => '2200', 'name' => 'Kewajiban Jangka Panjang', 'type' => 'liability', 'normal_balance' => 'credit', 'level' => 2, 'is_header' => true, 'parent_code' => '2000'],
            ['code' => '2210', 'name' => 'Hutang Bank', 'type' => 'liability', 'normal_balance' => 'credit', 'level' => 3, 'parent_code' => '2200'],

            // EKUITAS (3xxx)
            ['code' => '3000', 'name' => 'EKUITAS', 'type' => 'equity', 'normal_balance' => 'credit', 'level' => 1, 'is_header' => true],
            ['code' => '3100', 'name' => 'Modal Disetor', 'type' => 'equity', 'normal_balance' => 'credit', 'level' => 2, 'parent_code' => '3000'],
            ['code' => '3110', 'name' => 'Penyertaan Modal Desa', 'type' => 'equity', 'normal_balance' => 'credit', 'level' => 3, 'parent_code' => '3100'],
            ['code' => '3120', 'name' => 'Penyertaan Modal Masyarakat', 'type' => 'equity', 'normal_balance' => 'credit', 'level' => 3, 'parent_code' => '3100'],
            ['code' => '3200', 'name' => 'Laba Ditahan', 'type' => 'equity', 'normal_balance' => 'credit', 'level' => 2, 'parent_code' => '3000'],
            ['code' => '3300', 'name' => 'Laba/Rugi Tahun Berjalan', 'type' => 'equity', 'normal_balance' => 'credit', 'level' => 2, 'parent_code' => '3000'],

            // PENDAPATAN (4xxx)
            ['code' => '4000', 'name' => 'PENDAPATAN', 'type' => 'revenue', 'normal_balance' => 'credit', 'level' => 1, 'is_header' => true],
            ['code' => '4100', 'name' => 'Pendapatan Usaha', 'type' => 'revenue', 'normal_balance' => 'credit', 'level' => 2, 'is_header' => true, 'parent_code' => '4000'],
            ['code' => '4110', 'name' => 'Pendapatan Simpan Pinjam', 'type' => 'revenue', 'normal_balance' => 'credit', 'level' => 3, 'parent_code' => '4100'],
            ['code' => '4120', 'name' => 'Pendapatan Perdagangan', 'type' => 'revenue', 'normal_balance' => 'credit', 'level' => 3, 'parent_code' => '4100'],
            ['code' => '4130', 'name' => 'Pendapatan Pertanian', 'type' => 'revenue', 'normal_balance' => 'credit', 'level' => 3, 'parent_code' => '4100'],
            ['code' => '4200', 'name' => 'Pendapatan Lain-lain', 'type' => 'revenue', 'normal_balance' => 'credit', 'level' => 2, 'parent_code' => '4000'],
            ['code' => '4210', 'name' => 'Pendapatan Bunga', 'type' => 'revenue', 'normal_balance' => 'credit', 'level' => 3, 'parent_code' => '4200'],
            ['code' => '4220', 'name' => 'Pendapatan Hibah', 'type' => 'revenue', 'normal_balance' => 'credit', 'level' => 3, 'parent_code' => '4200'],

            // BEBAN (5xxx)
            ['code' => '5000', 'name' => 'BEBAN', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 1, 'is_header' => true],
            ['code' => '5100', 'name' => 'Beban Operasional', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 2, 'is_header' => true, 'parent_code' => '5000'],
            ['code' => '5110', 'name' => 'Beban Gaji & Tunjangan', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '5100'],
            ['code' => '5120', 'name' => 'Beban Listrik', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '5100'],
            ['code' => '5130', 'name' => 'Beban Air', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '5100'],
            ['code' => '5140', 'name' => 'Beban Telepon & Internet', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '5100'],
            ['code' => '5150', 'name' => 'Beban Transportasi', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '5100'],
            ['code' => '5160', 'name' => 'Beban Perlengkapan', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '5100'],
            ['code' => '5170', 'name' => 'Beban Administrasi', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '5100'],
            ['code' => '5180', 'name' => 'Beban Perawatan', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 3, 'parent_code' => '5100'],
            ['code' => '5200', 'name' => 'Beban Penyusutan', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 2, 'parent_code' => '5000'],
            ['code' => '5300', 'name' => 'Beban Pajak', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 2, 'parent_code' => '5000'],
            ['code' => '5400', 'name' => 'Beban Lain-lain', 'type' => 'expense', 'normal_balance' => 'debit', 'level' => 2, 'parent_code' => '5000'],
        ];

        $accountMap = [];
        foreach ($accounts as $data) {
            $parentCode = $data['parent_code'] ?? null;
            unset($data['parent_code']);
            
            if ($parentCode && isset($accountMap[$parentCode])) {
                $data['parent_id'] = $accountMap[$parentCode];
            }
            
            $account = ChartOfAccount::create($data);
            $accountMap[$account->code] = $account->id;
        }
    }

    private function seedTransactionCategories(): void
    {
        // Income categories
        $incomeCategories = [
            ['name' => 'Pendapatan Usaha', 'type' => 'income', 'description' => 'Pendapatan dari operasional unit usaha'],
            ['name' => 'Pendapatan Bunga', 'type' => 'income', 'description' => 'Pendapatan bunga bank/investasi'],
            ['name' => 'Pendapatan Hibah', 'type' => 'income', 'description' => 'Bantuan/hibah dari pemerintah/pihak lain'],
            ['name' => 'Pendapatan Lain-lain', 'type' => 'income', 'description' => 'Pendapatan lainnya'],
        ];

        // Expense categories
        $expenseCategories = [
            ['name' => 'Gaji & Tunjangan', 'type' => 'expense', 'description' => 'Pembayaran gaji dan tunjangan karyawan'],
            ['name' => 'Pembelian Bahan Baku', 'type' => 'expense', 'description' => 'Pembelian bahan baku usaha'],
            ['name' => 'Biaya Utilitas', 'type' => 'expense', 'description' => 'Listrik, air, telepon, internet'],
            ['name' => 'Biaya Transportasi', 'type' => 'expense', 'description' => 'Biaya transportasi operasional'],
            ['name' => 'Biaya Perawatan', 'type' => 'expense', 'description' => 'Perawatan aset dan peralatan'],
            ['name' => 'Biaya Administrasi', 'type' => 'expense', 'description' => 'Biaya administrasi dan perlengkapan kantor'],
            ['name' => 'Pajak & Retribusi', 'type' => 'expense', 'description' => 'Pembayaran pajak dan retribusi'],
            ['name' => 'Biaya Lain-lain', 'type' => 'expense', 'description' => 'Pengeluaran lainnya'],
        ];

        foreach (array_merge($incomeCategories, $expenseCategories) as $category) {
            TransactionCategory::create($category);
        }
    }
}
