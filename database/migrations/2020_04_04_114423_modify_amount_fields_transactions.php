<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class ModifyAmountFieldsTransactions
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class ModifyAmountFieldsTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('total_transaction');
            $table->bigInteger('gross_value')->after('balance')->default(0);
            $table->bigInteger('net_value')->after('gross_value')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('gross_value');
            $table->dropColumn('net_value');
            $table->bigInteger('total_transaction')->after('balance')->default(0);
        });
    }
}
