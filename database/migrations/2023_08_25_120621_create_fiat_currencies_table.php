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
        Schema::create('fiat_currencies', static function (Blueprint $table) {

            $table->uuid('id')->primary();
            $table->timestamps();

            $table->string('name', 255);
            $table->string('symbol', 32)->unique();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiat_currencies');
    }
};
