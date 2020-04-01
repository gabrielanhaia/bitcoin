<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class AddFieldStatusTransactions
 *
 * Add field 'status' for transaction table.
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class AddFieldStatusTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('status', ['PENDING', 'PROCESSED', 'NOT_PROCESSED'])
                ->after('type')
                ->index('transactions_status')
                ->default('PENDING');
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
            $table->dropColumn('status');
        });
    }
}
