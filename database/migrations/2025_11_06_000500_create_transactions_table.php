<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('cashier_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('subtotal', 14, 2);
            $table->decimal('discount', 14, 2)->default(0);
            $table->decimal('tax', 14, 2)->default(0);
            $table->decimal('total', 14, 2);
            $table->string('payment_method')->default('cash'); // cash|transfer
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('change_amount', 14, 2)->default(0);
            $table->string('status')->default('success');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['payment_method']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
