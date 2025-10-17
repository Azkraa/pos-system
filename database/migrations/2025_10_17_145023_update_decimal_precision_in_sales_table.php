<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('total', 15, 2)->change();
            $table->decimal('paid_amount', 15, 2)->change();
            $table->decimal('discount', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('total', 8, 2)->change();
            $table->decimal('paid_amount', 8, 2)->change();
            $table->decimal('discount', 8, 2)->change();
        });
    }
};
