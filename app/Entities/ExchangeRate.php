<?php


namespace App\Entities;

use Carbon\Carbon;

/**
 * Class ExchangeRate
 * @package App\Entities
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class ExchangeRate
{
    /** @var integer $id Identifier of the exchange_rate. */
    protected $id;

    /** @var Currency $currency Field with the currency related to this exchange rate. */
    protected $currency;

    /** @var Carbon $datetime Dete of this exchange rate. */
    protected $datetime;

    /** @var double $amount Amount of this currency equivalent at the bitcoint amount. */
    protected $amount;

    /** @var integer $bitcoinAmount Amount of bitcoins related to the amount (currency). */
    protected $bitcoinAmount;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ExchangeRate
     */
    public function setId(int $id): ExchangeRate
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     * @return ExchangeRate
     */
    public function setCurrency(Currency $currency): ExchangeRate
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getDatetime(): Carbon
    {
        return $this->datetime;
    }

    /**
     * @param Carbon $datetime
     * @return ExchangeRate
     */
    public function setDatetime(Carbon $datetime): ExchangeRate
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return ExchangeRate
     */
    public function setAmount(float $amount): ExchangeRate
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return int
     */
    public function getBitcoinAmount(): int
    {
        return $this->bitcoinAmount;
    }

    /**
     * @param int $bitcoinAmount
     * @return ExchangeRate
     */
    public function setBitcoinAmount(int $bitcoinAmount): ExchangeRate
    {
        $this->bitcoinAmount = $bitcoinAmount;
        return $this;
    }
}
