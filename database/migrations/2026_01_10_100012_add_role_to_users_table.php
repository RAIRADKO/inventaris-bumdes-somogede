<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['director', 'treasurer', 'unit_admin', 'supervisor', 'accountant'])
                  ->default('unit_admin')
                  ->after('email');
            $table->foreignId('business_unit_id')
                  ->nullable()
                  ->after('role')
                  ->constrained()
                  ->onDelete('set null');
            $table->boolean('is_active')->default(true)->after('business_unit_id');
            $table->string('phone')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['business_unit_id']);
            $table->dropColumn(['role', 'business_unit_id', 'is_active', 'phone']);
        });
    }
};
