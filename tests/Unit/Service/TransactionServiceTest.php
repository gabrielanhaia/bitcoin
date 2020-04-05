<?php


namespace Tests\Unit;

use App\Entities\ExchangeRate as ExchangeRateEntity;
use App\Repositories\{CurrencyRepository, SettingRepository, TransactionRepository, WalletRepository, UserRepository};
use App\Services\TransactionService;
use Tests\TestCase;

/**
 * Class TransactionServiceTest
 * @package Tests\Unit
 *
 * TODO: Implement more tests to cover 100%
 */
class TransactionServiceTest extends TestCase
{
    /**
     * Method responsible for converting bitcoins to another currency.
     *
     * @param string $currencyName The name of the currency to be searched.
     * @param int $bitCoinsAmount Amount of bitcoins to be converted.
     * @param float $expectedResult Expected result after the conversion.
     * @param ExchangeRateEntity|null $exchangeRateEntity Current exchange rate to be converted.
     *
     * @dataProvider dataProviderTestConvertBitCoinsToAnotherCurrency
     */
    public function testConvertBitCoinsToAnotherCurrency(
        string $currencyName,
        int $bitCoinsAmount,
        float $expectedResult = null,
        ExchangeRateEntity $exchangeRateEntity = null
    )
    {
        $currencyRepositoryMock = \Mockery::mock(CurrencyRepository::class);
        $currencyRepositoryMock->shouldReceive('findExchangeRateByExchangeName')
            ->once()
            ->with($currencyName)
            ->andReturn($exchangeRateEntity);

        $transactionRepositoryMock = \Mockery::mock(TransactionRepository::class);
        $settingRepositoryMock = \Mockery::mock(SettingRepository::class);
        $walletRepositoryMock = \Mockery::mock(WalletRepository::class);
        $userRepositoryMock = \Mockery::mock(UserRepository::class);

        $transactionService = new TransactionService(
            $transactionRepositoryMock,
            $settingRepositoryMock,
            $walletRepositoryMock,
            $userRepositoryMock,
            $currencyRepositoryMock
        );

        $result = $transactionService->convertBitCoinsToAnotherCurrency($bitCoinsAmount, $currencyName);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Data provider for the test {@see testConvertBitCoinsToAnotherCurrency}
     */
    public function dataProviderTestConvertBitCoinsToAnotherCurrency()
    {
        return [
            [
                'currency_name' => 'USD',
                'bit_coins_amount' => 1000,
                'expected_result' => null,
                'exchange_rate_entity_db_another_currency' => null,
            ],
            [
                'currency_name' => 'BRL',
                'bit_coins_amount' => 0,
                'expected_result' => 0,
                'exchange_rate_entity_db_another_currency' => (new ExchangeRateEntity)
                    ->setAmount(1)
                    ->setBitcoinAmount(1)
            ],
            [
                'currency_name' => 'BRL',
                'bit_coins_amount' => 2,
                'expected_result' => 2,
                'exchange_rate_entity_db_another_currency' => (new ExchangeRateEntity)
                    ->setAmount(1)
                    ->setBitcoinAmount(1)
            ],
            [
                'currency_name' => 'BRL',
                'bit_coins_amount' => 5,
                'expected_result' => 50,
                'exchange_rate_entity_db_another_currency' => (new ExchangeRateEntity)
                    ->setBitcoinAmount(1)
                    ->setAmount(10)
            ],
            [
                'currency_name' => 'EUR',
                'bit_coins_amount' => 112,
                'expected_result' => 622160.0,
                'exchange_rate_entity_db_another_currency' => (new ExchangeRateEntity)
                    ->setBitcoinAmount(1)
                    ->setAmount(5555)
            ],
            [
                'currency_name' => 'EUR',
                'bit_coins_amount' => 112,
                'expected_result' => 207386.66666666666,
                'exchange_rate_entity_db_another_currency' => (new ExchangeRateEntity)
                    ->setBitcoinAmount(3)
                    ->setAmount(5555)
            ],
        ];
    }
}
