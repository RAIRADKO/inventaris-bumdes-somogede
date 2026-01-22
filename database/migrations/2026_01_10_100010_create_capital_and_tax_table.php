<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modal & Ekuitas
        Schema::create('capital_records', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->date('date');
            $table->enum('type', [
                'initial_capital',      // Modal awal
                'village_investment',   // Penyertaan modal Desa
                'community_investment', // Penyertaan modal masyarakat
                'retained_earnings',    // Laba ditahan
                'dividend_distribution' // Pembagian SHU
            ]);
            $table->decimal('amount', 15, 2);
            $table->text('description');
            $table->string('contributor')->nullable(); // Nama penyetor
            $table->string('attachment')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('journal_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

        // Pajak
        Schema::create('tax_records', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->date('date');
            $table->date('due_date');
            $table->enum('type', [
                'pph21',        // PPh Pasal 21
                'pph23',        // PPh Pasal 23
                'ppn',          // PPN
                'local_tax',    // Pajak daerah
                'other'         // Lainnya
            ]);
            $table->decimal('base_amount', 15, 2); // DPP
            $table->decimal('tax_amount', 15, 2);
            $table->text('description');
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('attachment')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('journal_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_records');
        Schema::dropIfExists('capital_records');
    }
};
