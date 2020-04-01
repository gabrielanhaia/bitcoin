<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateTransactions responsible for creating the table to store the Bitcoin transactions.
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('wallet_id')->unsigned()->nullable(false);
            $table->enum('type', ['TRANSFER', 'DEBIT', 'CREDIT'])->nullable(false);
            $table->bigInteger('balance')->nullable()->default(0);
            $table->bigInteger('total_transaction')->nullable(false);
            $table->double('profit_percentage')->nullable()->default(0);
            $table->bigInteger('total_profit')->nullable()->default(0);
            $table->dateTime('requested_at')->nullable(false);
            $table->dateTime('processed_at')->nullable(false);
            $table->bigInteger('wallet_id_origin')->unsigned()->nullable(true);
            $table->bigInteger('wallet_id_destination')->unsigned()->nullable(true);
            $table->string('observation', 80)->nullable()->default('');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('wallet_id', 'wallet_id_transactions')
                ->references('id')
                ->on('wallets')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION')
                ->onDelete('NO ACTION')->onDelete('NO ACTION');

            $table->foreign('wallet_id_origin', 'wallet_id_origin_transactions')
                ->references('id')
                ->on('wallets')
                ->onUpdate('NO ACTION')->onDelete('NO ACTION')
                ->onDelete('NO ACTION')->onDelete('NO ACTION');

            $table->foreign('wallet_id_destination', 'wallet_id_destination_transactions')
                ->references('id')
                ->on('wallets')
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
        Schema::dropIfExists('transactions');
    }
}
