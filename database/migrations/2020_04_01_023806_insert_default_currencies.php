<?php

use App\Models\Currency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class InsertDefaultCurrencies responsible for inserting the default currencies available.
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class InsertDefaultCurrencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        Currency::create([
            'name' => 'USD',
            'created_at' => new DateTime()
        ]);

        Currency::create([
            'name' => 'EUR',
            'created_at' => new DateTime()
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
