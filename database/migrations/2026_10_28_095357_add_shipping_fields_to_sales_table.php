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
            $table->decimal('shipping_cost', 15, 2)->default(0)->after('discount_amount');
            $table->string('shipping_courier', 100)->nullable()->after('shipping_status');
            $table->string('shipping_destination', 255)->nullable()->after('shipping_courier');
            $table->integer('shipping_weight')->default(1000)->after('shipping_destination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_cost',
                'shipping_courier',
                'shipping_destination',
                'shipping_weight',
            ]);
        });
    }
};
