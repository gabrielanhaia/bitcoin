<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateWallets.
 *
 * Create the table responsible for the wallets to store the bitcoins.
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class CreateWallets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->string('name');
            $table->string('address', 60);
            $table->timestamps();
        });

        Schema::table('wallets', function (Blueprint $table) {
            $table->foreign('user_id', 'user_id_wallets')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('wallets');
    }
}
