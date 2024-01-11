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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->text('amount');
            $table->text('transaction_amount')->nullable();
            $table->text('payment_amount')->nullable();
            $table->foreignId("module")
                ->nullable()
                ->constrained("payement_modules", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->foreignId("client")
                ->nullable()
                ->constrained("clients", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->foreignId("type")
                ->nullable()
                ->constrained("transaction_types", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->foreignId("status")
                ->nullable()
                ->constrained("transaction_statuses", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");
            $table->string('date_transaction');

            $table->foreignId("account_owner")
                ->nullable()
                ->constrained("users", "id")
                ->onUpdate("CASCADE")
                ->onDelete("CASCADE");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
