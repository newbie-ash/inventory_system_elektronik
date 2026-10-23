<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'purchase_price')) {
                $table->decimal('purchase_price', 15, 2)->default(0)->after('description');
            }
            if (! Schema::hasColumn('products', 'selling_price')) {
                $table->decimal('selling_price', 15, 2)->default(0)->after('purchase_price');
            }
            if (! Schema::hasColumn('products', 'minimum_stock')) {
                $table->integer('minimum_stock')->default(5)->after('stock');
            }
            if (! Schema::hasColumn('products', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });

        // Sync legacy price & min_stock if existing
        if (Schema::hasColumn('products', 'price') && Schema::hasColumn('products', 'selling_price')) {
            DB::table('products')->where('selling_price', 0)->update([
                'selling_price' => DB::raw('price'),
                'purchase_price' => DB::raw('price * 0.80'),
            ]);
        }

        if (Schema::hasColumn('products', 'min_stock') && Schema::hasColumn('products', 'minimum_stock')) {
            DB::table('products')->update([
                'minimum_stock' => DB::raw('min_stock'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['purchase_price', 'selling_price', 'minimum_stock']);
        });
    }
};
