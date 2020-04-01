<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class InsertProfitSetting
 *
 * Migration created to insert the default profit setting.
 * (I saw this approach inserting on a migration in a different project)
 * (I think it is an interesting way to guarantee the insertion)
 */
class InsertProfitSetting extends Migration
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
            'name' => 'profit_transfers',
            'value' => 1.5,
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
