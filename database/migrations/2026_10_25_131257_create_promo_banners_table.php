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
        Schema::create('promo_banners', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->nullable()->default('PROMO SPESIAL GAJIAN:');
            $table->string('title')->default('Potongan hingga 15% untuk kategori PC & Gadget');
            $table->text('description')->nullable();
            $table->string('button_text')->default('Klaim Voucher');
            $table->string('button_url')->default('#katalog-grid');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_banners');
    }
};
