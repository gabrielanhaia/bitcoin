<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class ModifyTransactionTypeEnum
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class ModifyTransactionTypeEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "ALTER TABLE transactions MODIFY 
            type enum('TRANSFER_CREDIT', 'TRANSFER_DEBIT', 'DEBIT', 'CREDIT') NOT NULL;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement(
            "ALTER TABLE transactions MODIFY 
                type enum('TRANSFER', 'DEBIT', 'CREDIT') NOT NULL;"
        );
    }
}
