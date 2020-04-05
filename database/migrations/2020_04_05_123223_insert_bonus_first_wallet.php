<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Class InsertBonusFirstWallet
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class InsertBonusFirstWallet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        Setting::create([
            'name' => 'bonus_bitcoin_first_wallet',
            'value' => 100000000,
            'created_at' => new DateTime
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
