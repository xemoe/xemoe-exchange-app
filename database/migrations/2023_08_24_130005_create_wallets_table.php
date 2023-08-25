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
        Schema::create('wallets', static function (Blueprint $table) {

            $table->uuid('id')->primary();
            $table->timestamps();

            $table->string('address')->unique();
            $table->decimal('balance', 30, 18)->default(0);

            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('currency_id')->constrained('crypto_currencies')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
