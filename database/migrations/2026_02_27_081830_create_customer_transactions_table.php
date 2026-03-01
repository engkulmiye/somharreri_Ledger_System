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
        Schema::create('customer_transactions', function (Blueprint $table) {
            $table->id();

            $table->date('date');

            // Partner logic
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('manual_partner_name')->nullable();

            // Type
            $table->enum('type', ['debt', 'payment']);

            // Money
            $table->decimal('cash_ksh', 15, 2)->nullable();
            $table->decimal('rate', 8, 2)->nullable();
            $table->decimal('amount_usd', 15, 2);

            // Commission
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('commission_amount', 15, 2)->default(0);

            // Totals
            $table->decimal('total_amount', 15, 2);

            // Debt tracking
            $table->enum('status', ['open', 'paid'])->default('open');
            $table->date('paid_at')->nullable();

            $table->decimal('remaining_amount', 15, 2)->default(0);

            // Payment → Debt relation
            $table->foreignId('parent_debt_id')
                ->nullable()
                ->constrained('customer_transactions')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_transactions');
    }
};
