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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('company')->nullable();
            $table->string('username')->nullable();

            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('password');

            $table->string('active_compte_code')->nullable();
            $table->string('compte_actif')->default(false);

            $table->string('pass_code')->nullable();
            $table->string('pass_code_active')->default(true);
            $table->boolean('is_admin')->default(false);

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
