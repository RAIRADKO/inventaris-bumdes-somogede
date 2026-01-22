<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori Aset
        Schema::create('asset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('useful_life_years')->default(5); // Masa manfaat
            $table->decimal('depreciation_rate', 5, 2)->default(20); // Persentase penyusutan
            $table->enum('depreciation_method', ['straight_line', 'declining_balance'])->default('straight_line');
            $table->foreignId('account_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null'); // Akun aset
            $table->foreignId('depreciation_account_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null'); // Akun penyusutan
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Aset Tetap
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name');
            $table->foreignId('category_id')->constrained('asset_categories')->onDelete('restrict');
            $table->foreignId('business_unit_id')->nullable()->constrained()->onDelete('set null');
            $table->date('acquisition_date');
            $table->decimal('acquisition_cost', 15, 2);
            $table->decimal('salvage_value', 15, 2)->default(0); // Nilai residu
            $table->decimal('current_value', 15, 2); // Nilai buku saat ini
            $table->decimal('accumulated_depreciation', 15, 2)->default(0);
            $table->enum('condition', ['good', 'fair', 'poor', 'damaged'])->default('good');
            $table->string('location')->nullable();
            $table->string('serial_number')->nullable();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['active', 'disposed', 'sold', 'lost'])->default('active');
            $table->date('disposal_date')->nullable();
            $table->decimal('disposal_value', 15, 2)->nullable();
            $table->text('disposal_notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Penyusutan Aset
        Schema::create('asset_depreciations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->foreignId('fiscal_period_id')->constrained()->onDelete('restrict');
            $table->date('date');
            $table->decimal('amount', 15, 2);
            $table->decimal('accumulated_amount', 15, 2);
            $table->decimal('book_value', 15, 2);
            $table->foreignId('journal_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_depreciations');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('asset_categories');
    }
};
