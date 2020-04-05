<?php


namespace App\Repositories;

use App\Entities\ExchangeRate as ExchangeRateEntity;
use App\Models\Currency as CurrencyModel;
use App\Models\ExchangeRate as ExchangeRateModel;
use Carbon\Carbon;

/**
 * Class CurrencyRepository
 * @package App\Repositories
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class CurrencyRepository extends Repository
{
    /**
     * @var CurrencyModel
     */
    private $currencyModel;
    /**
     * @var ExchangeRateEntity
     */
    private $exchangeRateModel;

    /**
     * CurrencyRepository constructor.
     *
     * @param CurrencyModel $currencyModel
     * @param ExchangeRateModel $exchangeRateModel
     */
    public function __construct(CurrencyModel $currencyModel, ExchangeRateModel $exchangeRateModel)
    {
        $this->currencyModel = $currencyModel;
        $this->exchangeRateModel = $exchangeRateModel;
    }

    /**
     * Method responsible for searching an exchange rate by exchange name.
     *
     * @param string $exchangeName Exchange name to be searched.
     * @return ExchangeRateEntity
     */
    public function findExchangeRateByExchangeName(string $exchangeName): ?ExchangeRateEntity
    {
        $exchangeRateResult = $this->exchangeRateModel
            ->select('exchange_rates.*')
            ->join('currencies', 'currencies.id', '=', 'exchange_rates.currency_id')
            ->where('currencies.name', '=', $exchangeName)
            ->orderBy('exchange_rates.datetime', 'DESC')
            ->first();

        if (empty($exchangeRateResult)) {
            return null;
        }

        $exchangeRateEntity = new ExchangeRateEntity($exchangeRateResult->id);
        $exchangeRateEntity->setAmount($exchangeRateResult->amount)
            ->setBitcoinAmount($exchangeRateResult->bitcoin_amount)
            ->setDatetime(Carbon::createFromFormat('Y-m-d H:i:s', $exchangeRateResult->datetime));

        return $exchangeRateEntity;
    }
}
