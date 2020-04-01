<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddForeignCurrencyIdExchangeRateTable responsible for add a foreign key on the field
 * 'currency_id' at the table 'exchange_rates'.
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class AddForeignCurrencyIdExchangeRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->foreign('currency_id', 'currency_id_exchange_rates')
                ->references('id')
                ->on('currencies')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION')
                ->onDelete('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchange_rates', function (Blueprint $table) {
            $table->dropForeign('currency_id_exchange_rates');
        });
    }
}
