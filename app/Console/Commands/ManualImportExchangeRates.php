<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\ExchangeRate;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

/**
 * Class ManualImportExchangeRates
 *
 * I created this class to import the exchange rates manually.
 * The reason why I created it is just to be able to test the tables and endpoints.
 * The official exchange rates importer will be started if I have enough time.
 * I have a great ideas about that importer.
 *
 * @package App\Console\Commands
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class ManualImportExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paxful:exchange_rates:manual_import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manual importer for the exchange rates.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $exchangeRatesApi = 'https://blockchain.info/ticker';

        $guzzleHttpClient = new Client;

        $exchangeRatesResult = $guzzleHttpClient->get($exchangeRatesApi);
        $rawResult = $exchangeRatesResult->getBody()->getContents();

        $formattedResult = json_decode($rawResult, true);

        foreach ($formattedResult as $currencyName => $values) {
            $currency = Currency::where('name', '=', $currencyName)
                ->first();

            if (empty($currency)) {
                continue;
            }

            ExchangeRate::create([
                'currency_id' => $currency->id,
                'datetime' => new \DateTime,
                'amount' => $values['last'],
                'bitcoin_amount' => 100000000
            ]);
        }
    }
}
