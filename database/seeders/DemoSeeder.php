<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\BusinessUnit;
use App\Models\CapitalRecord;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\ExpenseTransaction;
use App\Models\FiscalPeriod;
use App\Models\IncomeTransaction;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\Payable;
use App\Models\Receivable;
use App\Models\Supplier;
use App\Models\TransactionCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create additional users
        $this->seedUsers();
        
        // Create customers
        $this->seedCustomers();
        
        // Create suppliers
        $this->seedSuppliers();
        
        // Create asset categories
        $this->seedAssetCategories();
        
        // Create assets
        $this->seedAssets();
        
        // Create journal entries with transactions
        $this->seedJournalsAndTransactions();
        
        // Create receivables
        $this->seedReceivables();
        
        // Create payables
        $this->seedPayables();
        
        // Create budgets
        $this->seedBudgets();
        
        // Create capital records
        $this->seedCapitalRecords();
    }

    private function seedUsers(): void
    {
        $users = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@bumdes-somogede.id',
                'password' => Hash::make('password'),
                'role' => 'treasurer',
                'is_active' => true,
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti@bumdes-somogede.id',
                'password' => Hash::make('password'),
                'role' => 'secretary',
                'is_active' => true,
            ],
            [
                'name' => 'Agus Wijaya',
                'email' => 'agus@bumdes-somogede.id',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => true,
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@bumdes-somogede.id',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }

    private function seedCustomers(): void
    {
        $customers = [
            ['code' => 'CUST0001', 'name' => 'Toko Pak Joko', 'phone' => '081234567890', 'email' => 'joko@email.com', 'address' => 'Jl. Raya Somogede No. 10, Dusun Karang', 'notes' => 'Pelanggan tetap unit perdagangan', 'is_active' => true],
            ['code' => 'CUST0002', 'name' => 'Warung Bu Sari', 'phone' => '081234567891', 'email' => 'sari@email.com', 'address' => 'Jl. Desa Somogede No. 25, Dusun Krajan', 'notes' => 'Pelanggan retail', 'is_active' => true],
            ['code' => 'CUST0003', 'name' => 'Koperasi Desa Maju', 'phone' => '081234567892', 'email' => 'kopmaju@email.com', 'address' => 'Jl. Koperasi No. 5, Desa Tetangga', 'notes' => 'Koperasi mitra', 'is_active' => true],
            ['code' => 'CUST0004', 'name' => 'Petani Pak Karjo', 'phone' => '081234567893', 'email' => 'karjo@email.com', 'address' => 'Dusun Sawahan, Desa Somogede', 'notes' => 'Anggota unit pertanian', 'is_active' => true],
            ['code' => 'CUST0005', 'name' => 'Kelompok Tani Subur', 'phone' => '081234567894', 'email' => 'poktan.subur@email.com', 'address' => 'Dusun Sawahan, Desa Somogede', 'notes' => 'Kelompok tani binaan', 'is_active' => true],
            ['code' => 'CUST0006', 'name' => 'UD Makmur Jaya', 'phone' => '081234567895', 'email' => 'udmakmur@email.com', 'address' => 'Jl. Pasar No. 15, Kecamatan', 'notes' => 'Distributor hasil pertanian', 'is_active' => true],
            ['code' => 'CUST0007', 'name' => 'Ny. Endang Supriyati', 'phone' => '081234567896', 'email' => 'endang@email.com', 'address' => 'Dusun Krajan RT 02', 'notes' => 'Nasabah simpan pinjam', 'is_active' => true],
            ['code' => 'CUST0008', 'name' => 'Bp. Suroto', 'phone' => '081234567897', 'email' => 'suroto@email.com', 'address' => 'Dusun Karang RT 05', 'notes' => 'Nasabah simpan pinjam', 'is_active' => true],
            ['code' => 'CUST0009', 'name' => 'CV Berkah Sejahtera', 'phone' => '081234567898', 'email' => 'cvberkah@email.com', 'address' => 'Jl. Industri No. 8, Kota', 'notes' => 'Pembeli hasil panen', 'is_active' => true],
            ['code' => 'CUST0010', 'name' => 'Rumah Makan Sederhana', 'phone' => '081234567899', 'email' => 'rmsederhana@email.com', 'address' => 'Jl. Raya Somogede No. 50', 'notes' => 'Pembeli sayuran rutin', 'is_active' => true],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }

    private function seedSuppliers(): void
    {
        $suppliers = [
            ['code' => 'SUPP0001', 'name' => 'PT Pupuk Indonesia', 'phone' => '02112345678', 'email' => 'sales@pupukindonesia.co.id', 'address' => 'Jl. Industri Pupuk No. 1, Jakarta', 'bank_name' => 'Bank Mandiri', 'bank_account' => '1234567890', 'notes' => 'Supplier pupuk subsidi', 'is_active' => true],
            ['code' => 'SUPP0002', 'name' => 'Toko Pertanian Jaya', 'phone' => '081987654321', 'email' => 'taniajaya@email.com', 'address' => 'Jl. Pasar Tani No. 12, Kota', 'bank_name' => 'BRI', 'bank_account' => '2345678901', 'notes' => 'Supplier alat pertanian', 'is_active' => true],
            ['code' => 'SUPP0003', 'name' => 'CV Benih Unggul', 'phone' => '081876543210', 'email' => 'benihunggul@email.com', 'address' => 'Jl. Pertanian No. 5, Kabupaten', 'bank_name' => 'BNI', 'bank_account' => '3456789012', 'notes' => 'Supplier benih tanaman', 'is_active' => true],
            ['code' => 'SUPP0004', 'name' => 'UD Sembako Sejahtera', 'phone' => '081765432109', 'email' => 'sembako@email.com', 'address' => 'Jl. Pasar Grosir No. 20, Kota', 'bank_name' => 'Bank Mandiri', 'bank_account' => '4567890123', 'notes' => 'Supplier sembako untuk warung', 'is_active' => true],
            ['code' => 'SUPP0005', 'name' => 'PT Ayam Gemuk', 'phone' => '02165432109', 'email' => 'ayamgemuk@email.com', 'address' => 'Jl. Peternakan No. 8, Kabupaten', 'bank_name' => 'BCA', 'bank_account' => '5678901234', 'notes' => 'Supplier pakan ternak', 'is_active' => true],
            ['code' => 'SUPP0006', 'name' => 'Toko Elektronik Maju', 'phone' => '081654321098', 'email' => 'tokomaju@email.com', 'address' => 'Jl. Elektronik No. 15, Kota', 'bank_name' => 'BRI', 'bank_account' => '6789012345', 'notes' => 'Supplier peralatan kantor', 'is_active' => true],
            ['code' => 'SUPP0007', 'name' => 'Distributor Obat Pertanian', 'phone' => '081543210987', 'email' => 'obattani@email.com', 'address' => 'Jl. Agro No. 25, Kota', 'bank_name' => 'Bank Mandiri', 'bank_account' => '7890123456', 'notes' => 'Supplier pestisida dan obat tanaman', 'is_active' => true],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }

    private function seedAssetCategories(): void
    {
        // Get account IDs for assets
        $bangunanAccount = ChartOfAccount::where('code', '1220')->first();
        $kendaraanAccount = ChartOfAccount::where('code', '1230')->first();
        $peralatanAccount = ChartOfAccount::where('code', '1240')->first();
        $penyusutanBangunan = ChartOfAccount::where('code', '1221')->first();
        $penyusutanKendaraan = ChartOfAccount::where('code', '1231')->first();
        $penyusutanPeralatan = ChartOfAccount::where('code', '1241')->first();

        $categories = [
            [
                'name' => 'Bangunan',
                'useful_life_years' => 20,
                'depreciation_rate' => 5.00,
                'depreciation_method' => 'straight_line',
                'account_id' => $bangunanAccount?->id,
                'depreciation_account_id' => $penyusutanBangunan?->id,
                'description' => 'Gedung dan bangunan kantor',
                'is_active' => true,
            ],
            [
                'name' => 'Kendaraan',
                'useful_life_years' => 8,
                'depreciation_rate' => 12.50,
                'depreciation_method' => 'straight_line',
                'account_id' => $kendaraanAccount?->id,
                'depreciation_account_id' => $penyusutanKendaraan?->id,
                'description' => 'Kendaraan operasional',
                'is_active' => true,
            ],
            [
                'name' => 'Peralatan Kantor',
                'useful_life_years' => 4,
                'depreciation_rate' => 25.00,
                'depreciation_method' => 'straight_line',
                'account_id' => $peralatanAccount?->id,
                'depreciation_account_id' => $penyusutanPeralatan?->id,
                'description' => 'Komputer, printer, dan peralatan kantor',
                'is_active' => true,
            ],
            [
                'name' => 'Peralatan Pertanian',
                'useful_life_years' => 5,
                'depreciation_rate' => 20.00,
                'depreciation_method' => 'straight_line',
                'account_id' => $peralatanAccount?->id,
                'depreciation_account_id' => $penyusutanPeralatan?->id,
                'description' => 'Traktor, alat pertanian, dan mesin',
                'is_active' => true,
            ],
            [
                'name' => 'Inventaris Toko',
                'useful_life_years' => 4,
                'depreciation_rate' => 25.00,
                'depreciation_method' => 'straight_line',
                'account_id' => $peralatanAccount?->id,
                'depreciation_account_id' => $penyusutanPeralatan?->id,
                'description' => 'Rak, etalase, dan perlengkapan toko',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            AssetCategory::create($category);
        }
    }

    private function seedAssets(): void
    {
        $admin = User::where('role', 'director')->first();
        $categories = AssetCategory::all();
        $units = BusinessUnit::all();
        
        $assets = [
            // Bangunan
            [
                'code' => 'AST-BAN-0001',
                'name' => 'Gedung Kantor Utama BUMDes',
                'category_id' => $categories->where('name', 'Bangunan')->first()?->id,
                'business_unit_id' => $units->first()?->id,
                'acquisition_date' => '2022-01-15',
                'acquisition_cost' => 250000000,
                'salvage_value' => 25000000,
                'current_value' => 227500000,
                'accumulated_depreciation' => 22500000,
                'condition' => 'good',
                'location' => 'Jl. Raya Somogede No. 1',
                'description' => 'Gedung kantor pusat BUMDes Somogede',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
            [
                'code' => 'AST-BAN-0002',
                'name' => 'Gudang Penyimpanan Hasil Pertanian',
                'category_id' => $categories->where('name', 'Bangunan')->first()?->id,
                'business_unit_id' => $units->where('code', 'UNIT-03')->first()?->id,
                'acquisition_date' => '2023-03-20',
                'acquisition_cost' => 150000000,
                'salvage_value' => 15000000,
                'current_value' => 143250000,
                'accumulated_depreciation' => 6750000,
                'condition' => 'good',
                'location' => 'Dusun Sawahan',
                'description' => 'Gudang penyimpanan padi dan hasil pertanian',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
            // Kendaraan
            [
                'code' => 'AST-KEN-0001',
                'name' => 'Mobil Pick Up Mitsubishi L300',
                'category_id' => $categories->where('name', 'Kendaraan')->first()?->id,
                'business_unit_id' => $units->where('code', 'UNIT-02')->first()?->id,
                'acquisition_date' => '2023-06-10',
                'acquisition_cost' => 180000000,
                'salvage_value' => 36000000,
                'current_value' => 168750000,
                'accumulated_depreciation' => 11250000,
                'condition' => 'good',
                'location' => 'Garasi Kantor BUMDes',
                'serial_number' => 'B 1234 ABC',
                'description' => 'Kendaraan operasional unit perdagangan',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
            [
                'code' => 'AST-KEN-0002',
                'name' => 'Motor Honda Vario 125',
                'category_id' => $categories->where('name', 'Kendaraan')->first()?->id,
                'business_unit_id' => $units->first()?->id,
                'acquisition_date' => '2024-02-01',
                'acquisition_cost' => 22000000,
                'salvage_value' => 4400000,
                'current_value' => 20166667,
                'accumulated_depreciation' => 1833333,
                'condition' => 'good',
                'location' => 'Garasi Kantor BUMDes',
                'serial_number' => 'AB 5678 DEF',
                'description' => 'Motor operasional untuk penagihan',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
            // Peralatan Kantor
            [
                'code' => 'AST-PER-0001',
                'name' => 'Komputer Desktop Dell',
                'category_id' => $categories->where('name', 'Peralatan Kantor')->first()?->id,
                'business_unit_id' => $units->first()?->id,
                'acquisition_date' => '2024-01-15',
                'acquisition_cost' => 12000000,
                'salvage_value' => 1200000,
                'current_value' => 9300000,
                'accumulated_depreciation' => 2700000,
                'condition' => 'good',
                'location' => 'Ruang Administrasi',
                'serial_number' => 'DELL-2024-001',
                'description' => 'Komputer untuk administrasi keuangan',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
            [
                'code' => 'AST-PER-0002',
                'name' => 'Printer Epson L3210',
                'category_id' => $categories->where('name', 'Peralatan Kantor')->first()?->id,
                'business_unit_id' => $units->first()?->id,
                'acquisition_date' => '2024-01-15',
                'acquisition_cost' => 3500000,
                'salvage_value' => 350000,
                'current_value' => 2712500,
                'accumulated_depreciation' => 787500,
                'condition' => 'good',
                'location' => 'Ruang Administrasi',
                'serial_number' => 'EPSON-2024-001',
                'description' => 'Printer untuk cetak laporan',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
            // Peralatan Pertanian
            [
                'code' => 'AST-TAN-0001',
                'name' => 'Traktor Tangan Kubota',
                'category_id' => $categories->where('name', 'Peralatan Pertanian')->first()?->id,
                'business_unit_id' => $units->where('code', 'UNIT-03')->first()?->id,
                'acquisition_date' => '2023-08-15',
                'acquisition_cost' => 45000000,
                'salvage_value' => 4500000,
                'current_value' => 38700000,
                'accumulated_depreciation' => 6300000,
                'condition' => 'good',
                'location' => 'Gudang Pertanian',
                'serial_number' => 'KBT-2023-001',
                'description' => 'Traktor untuk pengolahan lahan',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
            [
                'code' => 'AST-TAN-0002',
                'name' => 'Mesin Pompa Air Honda',
                'category_id' => $categories->where('name', 'Peralatan Pertanian')->first()?->id,
                'business_unit_id' => $units->where('code', 'UNIT-03')->first()?->id,
                'acquisition_date' => '2024-04-10',
                'acquisition_cost' => 8500000,
                'salvage_value' => 850000,
                'current_value' => 7437500,
                'accumulated_depreciation' => 1062500,
                'condition' => 'good',
                'location' => 'Sawah Blok A',
                'serial_number' => 'HND-2024-001',
                'description' => 'Pompa air untuk irigasi sawah',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
            // Inventaris Toko
            [
                'code' => 'AST-TOK-0001',
                'name' => 'Rak Display 3 Tingkat (Set 5 Unit)',
                'category_id' => $categories->where('name', 'Inventaris Toko')->first()?->id,
                'business_unit_id' => $units->where('code', 'UNIT-02')->first()?->id,
                'acquisition_date' => '2023-05-20',
                'acquisition_cost' => 15000000,
                'salvage_value' => 1500000,
                'current_value' => 12187500,
                'accumulated_depreciation' => 2812500,
                'condition' => 'good',
                'location' => 'Toko Unit Perdagangan',
                'description' => 'Rak display untuk produk toko',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
            [
                'code' => 'AST-TOK-0002',
                'name' => 'Timbangan Digital Camry',
                'category_id' => $categories->where('name', 'Inventaris Toko')->first()?->id,
                'business_unit_id' => $units->where('code', 'UNIT-02')->first()?->id,
                'acquisition_date' => '2024-01-10',
                'acquisition_cost' => 2500000,
                'salvage_value' => 250000,
                'current_value' => 1937500,
                'accumulated_depreciation' => 562500,
                'condition' => 'good',
                'location' => 'Toko Unit Perdagangan',
                'serial_number' => 'CMR-2024-001',
                'description' => 'Timbangan untuk toko',
                'status' => 'active',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($assets as $asset) {
            Asset::create($asset);
        }
    }

    private function seedJournalsAndTransactions(): void
    {
        $admin = User::where('role', 'director')->first();
        $treasurer = User::where('role', 'treasurer')->first();
        $fiscalPeriod = FiscalPeriod::where('is_active', true)->first();
        $units = BusinessUnit::all();
        $incomeCategories = TransactionCategory::where('type', 'income')->get();
        $expenseCategories = TransactionCategory::where('type', 'expense')->get();

        // Get accounts
        $kasAccount = ChartOfAccount::where('code', '1110')->first();
        $piutangAccount = ChartOfAccount::where('code', '1120')->first();
        $hutangAccount = ChartOfAccount::where('code', '2110')->first();
        $pendapatanSP = ChartOfAccount::where('code', '4110')->first();
        $pendapatanPerdagangan = ChartOfAccount::where('code', '4120')->first();
        $pendapatanPertanian = ChartOfAccount::where('code', '4130')->first();
        $bebanGaji = ChartOfAccount::where('code', '5110')->first();
        $bebanListrik = ChartOfAccount::where('code', '5120')->first();
        $bebanTransport = ChartOfAccount::where('code', '5150')->first();

        // Create income transactions for past 6 months
        $incomeData = [
            ['unit' => 'UNIT-01', 'category' => 'Pendapatan Usaha', 'source' => 'Bunga Pinjaman', 'amounts' => [8500000, 9200000, 8800000, 9500000, 10200000, 11000000]],
            ['unit' => 'UNIT-01', 'category' => 'Pendapatan Bunga', 'source' => 'Jasa Administrasi', 'amounts' => [1500000, 1650000, 1400000, 1800000, 1550000, 1700000]],
            ['unit' => 'UNIT-02', 'category' => 'Pendapatan Usaha', 'source' => 'Penjualan Barang', 'amounts' => [25000000, 28000000, 32000000, 27500000, 35000000, 38000000]],
            ['unit' => 'UNIT-03', 'category' => 'Pendapatan Usaha', 'source' => 'Penjualan Hasil Panen', 'amounts' => [15000000, 12000000, 22000000, 18000000, 25000000, 20000000]],
            ['unit' => 'UNIT-03', 'category' => 'Pendapatan Usaha', 'source' => 'Sewa Traktor', 'amounts' => [3500000, 4000000, 5500000, 4200000, 6000000, 5000000]],
        ];

        $expenseData = [
            ['unit' => 'UNIT-01', 'category' => 'Gaji & Tunjangan', 'recipient' => 'Karyawan', 'amounts' => [5000000, 5000000, 5000000, 5000000, 5500000, 5500000]],
            ['unit' => 'UNIT-01', 'category' => 'Biaya Utilitas', 'recipient' => 'PLN', 'amounts' => [850000, 920000, 780000, 950000, 880000, 920000]],
            ['unit' => 'UNIT-02', 'category' => 'Gaji & Tunjangan', 'recipient' => 'Karyawan Toko', 'amounts' => [4500000, 4500000, 4500000, 4500000, 4500000, 4800000]],
            ['unit' => 'UNIT-02', 'category' => 'Biaya Transportasi', 'recipient' => 'BBM Operasional', 'amounts' => [1200000, 1350000, 1450000, 1280000, 1500000, 1650000]],
            ['unit' => 'UNIT-03', 'category' => 'Gaji & Tunjangan', 'recipient' => 'Pekerja Pertanian', 'amounts' => [3500000, 3500000, 4200000, 3800000, 4500000, 4000000]],
            ['unit' => 'UNIT-03', 'category' => 'Pembelian Bahan Baku', 'recipient' => 'Supplier Pupuk', 'amounts' => [5500000, 4800000, 7200000, 6000000, 8500000, 7000000]],
        ];

        for ($monthOffset = 5; $monthOffset >= 0; $monthOffset--) {
            $date = Carbon::now()->subMonths($monthOffset)->startOfMonth()->addDays(rand(5, 20));
            
            // Create income transactions
            foreach ($incomeData as $income) {
                $unit = $units->where('code', $income['unit'])->first();
                $category = $incomeCategories->where('name', $income['category'])->first();
                $amount = $income['amounts'][$monthOffset] ?? 0;

                if (!$unit || !$category || $amount <= 0) continue;

                // Create Journal
                $journal = Journal::create([
                    'journal_number' => 'JRN-' . $date->format('Ymd') . '-' . str_pad(Journal::count() + 1, 4, '0', STR_PAD_LEFT),
                    'date' => $date,
                    'description' => 'Pendapatan ' . $income['source'] . ' - ' . $unit->name,
                    'business_unit_id' => $unit->id,
                    'fiscal_period_id' => $fiscalPeriod->id,
                    'status' => 'approved',
                    'type' => 'income',
                    'created_by' => $treasurer?->id ?? $admin->id,
                    'approved_by' => $admin->id,
                    'approved_at' => $date,
                ]);

                // Journal entries
                JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $kasAccount->id,
                    'description' => 'Penerimaan kas',
                    'debit' => $amount,
                    'credit' => 0,
                ]);
                JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $income['unit'] === 'UNIT-01' ? $pendapatanSP->id : ($income['unit'] === 'UNIT-02' ? $pendapatanPerdagangan->id : $pendapatanPertanian->id),
                    'description' => 'Pendapatan ' . $income['source'],
                    'debit' => 0,
                    'credit' => $amount,
                ]);

                // Create income transaction
                IncomeTransaction::create([
                    'transaction_number' => 'INC-' . $date->format('Ymd') . '-' . str_pad(IncomeTransaction::count() + 1, 4, '0', STR_PAD_LEFT),
                    'date' => $date,
                    'category_id' => $category->id,
                    'business_unit_id' => $unit->id,
                    'amount' => $amount,
                    'description' => $income['source'],
                    'source' => $income['source'],
                    'status' => 'approved',
                    'created_by' => $treasurer?->id ?? $admin->id,
                    'approved_by' => $admin->id,
                    'approved_at' => $date,
                    'journal_id' => $journal->id,
                ]);
            }

            // Create expense transactions
            foreach ($expenseData as $expense) {
                $unit = $units->where('code', $expense['unit'])->first();
                $category = $expenseCategories->where('name', $expense['category'])->first();
                $amount = $expense['amounts'][$monthOffset] ?? 0;

                if (!$unit || !$category || $amount <= 0) continue;

                $expenseDate = $date->copy()->addDays(rand(1, 5));

                // Create Journal
                $journal = Journal::create([
                    'journal_number' => 'JRN-' . $expenseDate->format('Ymd') . '-' . str_pad(Journal::count() + 1, 4, '0', STR_PAD_LEFT),
                    'date' => $expenseDate,
                    'description' => 'Pembayaran ' . $expense['category'] . ' - ' . $unit->name,
                    'business_unit_id' => $unit->id,
                    'fiscal_period_id' => $fiscalPeriod->id,
                    'status' => 'approved',
                    'type' => 'expense',
                    'created_by' => $treasurer?->id ?? $admin->id,
                    'approved_by' => $admin->id,
                    'approved_at' => $expenseDate,
                ]);

                // Determine expense account
                $expenseAccount = $bebanGaji;
                if (str_contains($expense['category'], 'Utilitas')) $expenseAccount = $bebanListrik;
                if (str_contains($expense['category'], 'Transportasi')) $expenseAccount = $bebanTransport;

                JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $expenseAccount->id,
                    'description' => 'Beban ' . $expense['category'],
                    'debit' => $amount,
                    'credit' => 0,
                ]);
                JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $kasAccount->id,
                    'description' => 'Pengeluaran kas',
                    'debit' => 0,
                    'credit' => $amount,
                ]);

                // Create expense transaction
                ExpenseTransaction::create([
                    'transaction_number' => 'EXP-' . $expenseDate->format('Ymd') . '-' . str_pad(ExpenseTransaction::count() + 1, 4, '0', STR_PAD_LEFT),
                    'date' => $expenseDate,
                    'category_id' => $category->id,
                    'business_unit_id' => $unit->id,
                    'amount' => $amount,
                    'description' => $expense['category'] . ' ' . $expenseDate->format('F Y'),
                    'recipient' => $expense['recipient'],
                    'status' => 'approved',
                    'created_by' => $treasurer?->id ?? $admin->id,
                    'approved_by' => $admin->id,
                    'approved_at' => $expenseDate,
                    'journal_id' => $journal->id,
                ]);
            }
        }
    }

    private function seedReceivables(): void
    {
        $admin = User::where('role', 'director')->first();
        $customers = Customer::all();
        $units = BusinessUnit::all();

        $receivables = [
            ['customer' => 'CUST0001', 'unit' => 'UNIT-02', 'amount' => 15000000, 'days_ago' => 45, 'due_days' => 30, 'status' => 'partial', 'paid' => 10000000, 'desc' => 'Pembelian barang dagangan'],
            ['customer' => 'CUST0002', 'unit' => 'UNIT-02', 'amount' => 5000000, 'days_ago' => 30, 'due_days' => 30, 'status' => 'unpaid', 'paid' => 0, 'desc' => 'Pembelian sembako'],
            ['customer' => 'CUST0003', 'unit' => 'UNIT-01', 'amount' => 25000000, 'days_ago' => 60, 'due_days' => 90, 'status' => 'partial', 'paid' => 15000000, 'desc' => 'Pinjaman modal usaha'],
            ['customer' => 'CUST0004', 'unit' => 'UNIT-01', 'amount' => 10000000, 'days_ago' => 40, 'due_days' => 60, 'status' => 'unpaid', 'paid' => 0, 'desc' => 'Pinjaman pertanian'],
            ['customer' => 'CUST0005', 'unit' => 'UNIT-03', 'amount' => 8500000, 'days_ago' => 25, 'due_days' => 30, 'status' => 'unpaid', 'paid' => 0, 'desc' => 'Sewa alat pertanian'],
            ['customer' => 'CUST0006', 'unit' => 'UNIT-03', 'amount' => 35000000, 'days_ago' => 20, 'due_days' => 14, 'status' => 'overdue', 'paid' => 0, 'desc' => 'Penjualan hasil panen'],
            ['customer' => 'CUST0007', 'unit' => 'UNIT-01', 'amount' => 15000000, 'days_ago' => 90, 'due_days' => 120, 'status' => 'partial', 'paid' => 8000000, 'desc' => 'Pinjaman modal usaha'],
            ['customer' => 'CUST0008', 'unit' => 'UNIT-01', 'amount' => 7500000, 'days_ago' => 30, 'due_days' => 60, 'status' => 'paid', 'paid' => 7500000, 'desc' => 'Pinjaman konsumtif'],
            ['customer' => 'CUST0009', 'unit' => 'UNIT-03', 'amount' => 45000000, 'days_ago' => 15, 'due_days' => 30, 'status' => 'unpaid', 'paid' => 0, 'desc' => 'Penjualan gabah'],
            ['customer' => 'CUST0010', 'unit' => 'UNIT-02', 'amount' => 3500000, 'days_ago' => 10, 'due_days' => 14, 'status' => 'unpaid', 'paid' => 0, 'desc' => 'Pembelian sayuran'],
        ];

        foreach ($receivables as $data) {
            $customer = $customers->where('code', $data['customer'])->first();
            $unit = $units->where('code', $data['unit'])->first();
            
            if (!$customer || !$unit) continue;

            $date = Carbon::now()->subDays($data['days_ago']);
            $dueDate = $date->copy()->addDays($data['due_days']);
            $remaining = $data['amount'] - $data['paid'];

            Receivable::create([
                'invoice_number' => 'INV-' . $date->format('Ymd') . '-' . str_pad(Receivable::count() + 1, 4, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'business_unit_id' => $unit->id,
                'date' => $date,
                'due_date' => $dueDate,
                'amount' => $data['amount'],
                'paid_amount' => $data['paid'],
                'remaining_amount' => $remaining,
                'description' => $data['desc'],
                'status' => $data['status'],
                'created_by' => $admin->id,
            ]);
        }
    }

    private function seedPayables(): void
    {
        $admin = User::where('role', 'director')->first();
        $suppliers = Supplier::all();
        $units = BusinessUnit::all();

        $payables = [
            ['supplier' => 'SUPP0001', 'unit' => 'UNIT-03', 'amount' => 25000000, 'days_ago' => 35, 'due_days' => 30, 'status' => 'partial', 'paid' => 15000000, 'desc' => 'Pembelian pupuk urea dan NPK'],
            ['supplier' => 'SUPP0002', 'unit' => 'UNIT-03', 'amount' => 12000000, 'days_ago' => 20, 'due_days' => 30, 'status' => 'unpaid', 'paid' => 0, 'desc' => 'Pembelian alat pertanian'],
            ['supplier' => 'SUPP0003', 'unit' => 'UNIT-03', 'amount' => 8500000, 'days_ago' => 45, 'due_days' => 30, 'status' => 'overdue', 'paid' => 0, 'desc' => 'Pembelian benih padi'],
            ['supplier' => 'SUPP0004', 'unit' => 'UNIT-02', 'amount' => 35000000, 'days_ago' => 15, 'due_days' => 14, 'status' => 'unpaid', 'paid' => 0, 'desc' => 'Pengadaan stok sembako'],
            ['supplier' => 'SUPP0005', 'unit' => 'UNIT-03', 'amount' => 15000000, 'days_ago' => 25, 'due_days' => 30, 'status' => 'partial', 'paid' => 7500000, 'desc' => 'Pembelian pakan ternak'],
            ['supplier' => 'SUPP0006', 'unit' => 'UNIT-01', 'amount' => 6500000, 'days_ago' => 30, 'due_days' => 30, 'status' => 'paid', 'paid' => 6500000, 'desc' => 'Pembelian peralatan kantor'],
            ['supplier' => 'SUPP0007', 'unit' => 'UNIT-03', 'amount' => 9500000, 'days_ago' => 10, 'due_days' => 30, 'status' => 'unpaid', 'paid' => 0, 'desc' => 'Pembelian pestisida'],
        ];

        foreach ($payables as $data) {
            $supplier = $suppliers->where('code', $data['supplier'])->first();
            $unit = $units->where('code', $data['unit'])->first();
            
            if (!$supplier || !$unit) continue;

            $date = Carbon::now()->subDays($data['days_ago']);
            $dueDate = $date->copy()->addDays($data['due_days']);
            $remaining = $data['amount'] - $data['paid'];

            Payable::create([
                'invoice_number' => 'PAY-' . $date->format('Ymd') . '-' . str_pad(Payable::count() + 1, 4, '0', STR_PAD_LEFT),
                'supplier_id' => $supplier->id,
                'business_unit_id' => $unit->id,
                'date' => $date,
                'due_date' => $dueDate,
                'amount' => $data['amount'],
                'paid_amount' => $data['paid'],
                'remaining_amount' => $remaining,
                'description' => $data['desc'],
                'status' => $data['status'],
                'created_by' => $admin->id,
            ]);
        }
    }

    private function seedBudgets(): void
    {
        $admin = User::where('role', 'director')->first();
        $fiscalPeriod = FiscalPeriod::where('is_active', true)->first();
        $units = BusinessUnit::all();
        $expenseCategories = TransactionCategory::where('type', 'expense')->get();
        $accounts = ChartOfAccount::whereIn('code', ['5110', '5120', '5130', '5140', '5150', '5160', '5170', '5180'])->get();

        $budgetData = [
            [
                'name' => 'Anggaran Operasional Unit Simpan Pinjam 2026',
                'unit' => 'UNIT-01',
                'total' => 85000000,
                'items' => [
                    ['category' => 'Gaji & Tunjangan', 'account' => '5110', 'planned' => 66000000, 'realized' => 36000000],
                    ['category' => 'Biaya Utilitas', 'account' => '5120', 'planned' => 12000000, 'realized' => 5300000],
                    ['category' => 'Biaya Administrasi', 'account' => '5170', 'planned' => 7000000, 'realized' => 3500000],
                ]
            ],
            [
                'name' => 'Anggaran Operasional Unit Perdagangan 2026',
                'unit' => 'UNIT-02',
                'total' => 120000000,
                'items' => [
                    ['category' => 'Gaji & Tunjangan', 'account' => '5110', 'planned' => 57600000, 'realized' => 27300000],
                    ['category' => 'Biaya Transportasi', 'account' => '5150', 'planned' => 18000000, 'realized' => 8430000],
                    ['category' => 'Biaya Perawatan', 'account' => '5180', 'planned' => 24000000, 'realized' => 10500000],
                    ['category' => 'Biaya Administrasi', 'account' => '5170', 'planned' => 10800000, 'realized' => 5200000],
                ]
            ],
            [
                'name' => 'Anggaran Operasional Unit Pertanian 2026',
                'unit' => 'UNIT-03',
                'total' => 180000000,
                'items' => [
                    ['category' => 'Gaji & Tunjangan', 'account' => '5110', 'planned' => 48000000, 'realized' => 23500000],
                    ['category' => 'Pembelian Bahan Baku', 'account' => '5160', 'planned' => 96000000, 'realized' => 39000000],
                    ['category' => 'Biaya Transportasi', 'account' => '5150', 'planned' => 18000000, 'realized' => 8800000],
                    ['category' => 'Biaya Perawatan', 'account' => '5180', 'planned' => 18000000, 'realized' => 7500000],
                ]
            ],
        ];

        foreach ($budgetData as $data) {
            $unit = $units->where('code', $data['unit'])->first();
            if (!$unit) continue;

            $budget = Budget::create([
                'name' => $data['name'],
                'fiscal_period_id' => $fiscalPeriod->id,
                'business_unit_id' => $unit->id,
                'total_amount' => $data['total'],
                'status' => 'approved',
                'description' => 'Anggaran operasional tahun 2026 untuk ' . $unit->name,
                'created_by' => $admin->id,
                'approved_by' => $admin->id,
                'approved_at' => Carbon::now()->startOfYear(),
            ]);

            foreach ($data['items'] as $item) {
                $category = $expenseCategories->where('name', $item['category'])->first();
                $account = $accounts->where('code', $item['account'])->first();

                BudgetItem::create([
                    'budget_id' => $budget->id,
                    'account_id' => $account?->id,
                    'category_id' => $category?->id,
                    'description' => $item['category'],
                    'planned_amount' => $item['planned'],
                    'realized_amount' => $item['realized'],
                    'variance' => $item['planned'] - $item['realized'],
                ]);
            }
        }
    }

    private function seedCapitalRecords(): void
    {
        $admin = User::where('role', 'director')->first();

        $capitals = [
            ['type' => 'initial_capital', 'amount' => 500000000, 'contributor' => 'Pemerintah Desa Somogede', 'date' => '2021-01-15', 'desc' => 'Modal awal pendirian BUMDes'],
            ['type' => 'village_investment', 'amount' => 100000000, 'contributor' => 'APBDes Tahun 2022', 'date' => '2022-03-01', 'desc' => 'Tambahan penyertaan modal dari APBDes'],
            ['type' => 'village_investment', 'amount' => 150000000, 'contributor' => 'APBDes Tahun 2023', 'date' => '2023-02-15', 'desc' => 'Penyertaan modal untuk pengembangan unit pertanian'],
            ['type' => 'community_investment', 'amount' => 75000000, 'contributor' => 'Masyarakat Desa Somogede', 'date' => '2023-06-10', 'desc' => 'Investasi masyarakat untuk unit simpan pinjam'],
            ['type' => 'retained_earnings', 'amount' => 85000000, 'contributor' => 'Laba Tahun 2024', 'date' => '2025-01-20', 'desc' => 'Alokasi laba ditahan dari tahun buku 2024'],
            ['type' => 'village_investment', 'amount' => 200000000, 'contributor' => 'APBDes Tahun 2025', 'date' => '2025-02-01', 'desc' => 'Penyertaan modal untuk pengembangan unit perdagangan'],
            ['type' => 'dividend_distribution', 'amount' => -25000000, 'contributor' => 'Pembagian SHU 2024', 'date' => '2025-03-15', 'desc' => 'Pembagian SHU kepada pemerintah desa dan masyarakat'],
        ];

        foreach ($capitals as $capital) {
            $date = Carbon::parse($capital['date']);

            CapitalRecord::create([
                'reference_number' => 'CAP-' . $date->format('Ymd') . '-' . str_pad(CapitalRecord::count() + 1, 4, '0', STR_PAD_LEFT),
                'date' => $date,
                'type' => $capital['type'],
                'amount' => abs($capital['amount']),
                'description' => $capital['desc'],
                'contributor' => $capital['contributor'],
                'created_by' => $admin->id,
            ]);
        }
    }
}
