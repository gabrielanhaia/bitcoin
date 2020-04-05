<?php


namespace Tests\Unit\Repositories;

use App\Entities\ExchangeRate as ExchangeRateEntity;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Repositories\CurrencyRepository;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Class CurrencyRepositoryTest
 * @package Tests\Unit\Repositories
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class CurrencyRepositoryTest extends TestCase
{
    /**
     * Test searching for an exchange rate by exchange name.
     * (Exchange rate not found).
     */
    public function testFindExchangeRateByExchangeNameExchangeNotFound()
    {
        $exchangeName = 'USD';

        $currencyEloquentModel = \Mockery::mock(Currency::class);

        $exchangeRateEloquentModel = \Mockery::mock(ExchangeRate::class);
        $exchangeRateEloquentModel->shouldReceive('select')
            ->once()
            ->with('exchange_rates.*')
            ->andReturnSelf();

        $exchangeRateEloquentModel->shouldReceive('join')
            ->once()
            ->with('currencies', 'currencies.id', '=', 'exchange_rates.currency_id')
            ->andReturnSelf();

        $exchangeRateEloquentModel->shouldReceive('where')
            ->once()
            ->with('currencies.name', '=', $exchangeName)
            ->andReturnSelf();

        $exchangeRateEloquentModel->shouldReceive('orderBy')
            ->once()
            ->with('exchange_rates.datetime', 'DESC')
            ->andReturnSelf();

        $exchangeRateEloquentModel->shouldReceive('first')
            ->once()
            ->withNoArgs()
            ->andReturnNull();

        $currencyRepository = new CurrencyRepository($currencyEloquentModel, $exchangeRateEloquentModel);
        $result = $currencyRepository->findExchangeRateByExchangeName($exchangeName);

        $this->assertNull($result);
    }

    /**
     * Test success searching for an exchange rate by exchange name.
     */
    public function testFindExchangeRateByExchangeNameExchange()
    {
        $exchangeName = 'USD';

        $currencyEloquentModel = \Mockery::mock(Currency::class);

        $exchangeRateEloquentModel = \Mockery::mock(ExchangeRate::class);
        $exchangeRateEloquentModel->shouldReceive('select')
            ->once()
            ->with('exchange_rates.*')
            ->andReturnSelf();

        $exchangeRateEloquentModel->shouldReceive('join')
            ->once()
            ->with('currencies', 'currencies.id', '=', 'exchange_rates.currency_id')
            ->andReturnSelf();

        $exchangeRateEloquentModel->shouldReceive('where')
            ->once()
            ->with('currencies.name', '=', $exchangeName)
            ->andReturnSelf();

        $exchangeRateEloquentModel->shouldReceive('orderBy')
            ->once()
            ->with('exchange_rates.datetime', 'DESC')
            ->andReturnSelf();

        $exchangeRateEloquentModel->shouldReceive('first')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $exchangeRateId = 3212442;
        $exchangeRateAmount = 10000000;
        $exchangeRateBitCoinsAmount = 3000.4;
        $exchangeRateDateTimeString = '1993-12-01 10:11:12';

        $exchangeRateEloquentModel->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn($exchangeRateId);

        $exchangeRateEloquentModel->shouldReceive('getAttribute')
            ->with('amount')
            ->andReturn($exchangeRateAmount);

        $exchangeRateEloquentModel->shouldReceive('getAttribute')
            ->with('bitcoin_amount')
            ->andReturn($exchangeRateBitCoinsAmount);

        $exchangeRateEloquentModel->shouldReceive('getAttribute')
            ->with('datetime')
            ->andReturn($exchangeRateDateTimeString);

        $currencyRepository = new CurrencyRepository($currencyEloquentModel, $exchangeRateEloquentModel);
        $resultExchangeRate = $currencyRepository->findExchangeRateByExchangeName($exchangeName);


        $exchangeRateEntityExpectedResult = new ExchangeRateEntity($exchangeRateId);
        $exchangeRateEntityExpectedResult->setAmount($exchangeRateAmount)
            ->setBitcoinAmount($exchangeRateBitCoinsAmount)
            ->setDatetime(Carbon::createFromFormat('Y-m-d H:i:s', $exchangeRateDateTimeString));

        $this->assertEquals($exchangeRateEntityExpectedResult, $resultExchangeRate);
    }
}
