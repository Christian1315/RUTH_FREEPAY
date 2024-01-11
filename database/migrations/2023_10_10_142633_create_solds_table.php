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
        Schema::create('solds', function (Blueprint $table) {
            $table->id();
            // $table->foreignId("session")
            //     ->nullable()
            //     ->constrained('user_sessions', 'id')
            //     ->onUpdate("CASCADE")
            //     ->onDelete("CASCADE");
            $table->text("comments")->nullable();
            $table->integer("amount")->default(0);
            $table->text("reference")->nullable();
            // $table->foreignId("agency")
            //     ->nullable()
            //     ->constrained("agencies", "id")
            //     ->onDelete("CASCADE")
            //     ->onUpdate("CASCADE");

            // $table->foreignId("pos")
            //     ->nullable()
            //     ->constrained("pos", "id")
            //     ->onDelete("CASCADE")
            //     ->onUpdate("CASCADE");

            $table->foreignId("owner")
                ->nullable()
                ->constrained("users", "id")
                ->onDelete("CASCADE")
                ->onUpdate("CASCADE");

            $table->foreignId("manager")
                ->nullable()
                ->constrained("users", "id")
                ->onDelete("CASCADE")
                ->onUpdate("CASCADE");

            // $table->foreignId("module")
            //     ->nullable()
            //     ->constrained("modules", "id")
            //     ->onDelete("CASCADE")
            //     ->onUpdate("CASCADE");

            $table->foreignId("status")
                ->nullable()
                ->constrained("sold_statuses", "id")
                ->onDelete("CASCADE")
                ->onUpdate("CASCADE");

            $table->boolean("visible")->default(true);
            $table->string("credited_at")->nullable();
            $table->string("decredited_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solds');
    }
};
