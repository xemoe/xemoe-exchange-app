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
        Schema::create('trading_pairs', static function (Blueprint $table) {

            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();

            $table->string('base_currency_symbol', 32);
            $table->string('quote_currency_symbol', 32);
            $table->string('pair_symbol', 64);

            //
            // Create morphs for the trading pair.
            // - base_currency_id should be used for both `fiat_currencies` and `crypto_currencies` tables.
            // - quote_currency_id should be used for both `fiat_currencies` and `crypto_currencies` tables.
            //
            $table->uuidMorphs('base_currency');
            $table->uuidMorphs('quote_currency');

            //
            // Create a unique index on the base_currency_id and quote_currency_id columns.
            // This will prevent duplicate trading pairs from being created.
            //
            $table->unique(['base_currency_id', 'quote_currency_id']);

            //
            // Index the pair_symbol column.
            //
            $table->index('base_currency_symbol');
            $table->index('quote_currency_symbol');
            $table->index('pair_symbol');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_pairs');
    }
};
