<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class ChangeProcessedAtTransactionsNullable responsible for
 * change the field 'processed_at' to nullable.
 *
 * When a new transaction is stored in the database, this field use to be null,
 * it is only updated when the transaction is processed.
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class ChangeProcessedAtTransactionsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `transactions` MODIFY `processed_at` datetime DEFAULT null;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `transactions` MODIFY `processed_at` datetime NOT NULL;');
    }
}
