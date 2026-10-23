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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('buyer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sale_number', 50)->unique();
            $table->decimal('total', 15, 2)->default(0);
            $table->enum('status', ['completed', 'cancelled'])->default('completed');
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'failed', 'refunded'])->default('paid');
            $table->string('payment_method', 50)->default('cash');
            $table->string('snap_token')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('sold_at')->useCurrent();
            $table->timestamps();

            $table->index('sold_at');
            $table->index('status');
            $table->index('buyer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
