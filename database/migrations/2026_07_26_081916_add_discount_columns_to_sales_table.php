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
            $table->string('discount_type')->default('fixed')->after('total');   // 'fixed' or 'percentage'
            $table->decimal('discount_value')->default(0)->after('discount_type'); // raw value user typed (5 or 5%)
            $table->decimal('discount_amount')->default(0)->after('discount_value'); // calculated tk amount
            $table->decimal('grand_total')->default(0)->after('discount_amount'); // total - discount_amount
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_value', 'discount_amount', 'grand_total']);
        });
    }
};
