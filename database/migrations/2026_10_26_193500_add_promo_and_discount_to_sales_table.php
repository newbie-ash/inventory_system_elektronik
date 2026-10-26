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
            $table->decimal('subtotal', 15, 2)->default(0)->after('sale_number');
            $table->decimal('discount_amount', 15, 2)->default(0)->after('subtotal');
            $table->string('promo_code', 50)->nullable()->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'discount_amount', 'promo_code']);
        });
    }
};
