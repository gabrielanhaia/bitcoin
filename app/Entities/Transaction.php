<?php


namespace App\Entities;

use App\Entities\Enums\TransactionStatusEnum;
use App\Entities\Enums\TransactionTypeEnum;
use Carbon\Carbon;

/**
 * Class Transaction
 * @package App\Entities
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class Transaction extends AbstractEntity
{
    /** @var Wallet $wallet Wallet that owns the transaction. */
    protected $wallet;

    /** @var TransactionTypeEnum $type Type of transaction. */
    protected $type;

    /** @var TransactionStatusEnum $status Status of the transaction. */
    protected $status;

    /** @var integer $balance Total of bitcoins at the moment of the transaction. */
    protected $balance;

    /** @var integer $totalTransaction Total amount of the transaction. */
    protected $totalTransaction;

    /** @var float $profitPercentage Percentage of the profit in this transaction. */
    protected $profitPercentage;

    /** @var float $totalProfit Total amount of the transaction. */
    protected $totalProfit;

    /** @var Carbon $requestedAt Datetime of the request. */
    protected $requestedAt;

    /** @var Carbon $processedAt Datetime where the transaction was processed. */
    protected $processedAt;

    /** @var Wallet $walletOrigin Wallet where the transaction was created. */
    protected $walletOrigin;

    /** @var Wallet $walletDestination Waller where the money was sent. */
    protected $walletDestination;

    /** @var string $observation Observations related to the transaction. */
    protected $observation;

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'wallet' => $this->getWallet() ? $this->getWallet()->toArray() : [],
            'type' => $this->getType() ? $this->getType()->value() : '',
            'status' => $this->getStatus() ? $this->getStatus()->value() : '',
            'balance' => $this->getBalance(),
            'total_transaction' => $this->getTotalTransaction(),
            'profit_percentage' => $this->getProfitPercentage(),
            'total_profit' => $this->getTotalProfit(),
            'requested_at' => $this->getRequestedAt(),
            'processed_at' => $this->getProcessedAt(),
            'wallet_origin' => $this->getWalletOrigin() ? $this->getWalletOrigin()->toArray() : [],
            'wallet_destination' => $this->getWalletDestination() ? $this->getWalletDestination()->toArray() : [],
            'observation' => $this->getObservation(),
        ];
    }

    /**
     * @return Wallet
     */
    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    /**
     * @param Wallet $wallet
     * @return Transaction
     */
    public function setWallet(Wallet $wallet): Transaction
    {
        $this->wallet = $wallet;
        return $this;
    }

    /**
     * @return int
     */
    public function getBalance(): int
    {
        return $this->balance;
    }

    /**
     * @param int $balance
     * @return Transaction
     */
    public function setBalance(int $balance): Transaction
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalTransaction(): int
    {
        return $this->totalTransaction;
    }

    /**
     * @param int $totalTransaction
     * @return Transaction
     */
    public function setTotalTransaction(int $totalTransaction): Transaction
    {
        $this->totalTransaction = $totalTransaction;
        return $this;
    }

    /**
     * @return float
     */
    public function getProfitPercentage(): float
    {
        return $this->profitPercentage;
    }

    /**
     * @param float $profitPercentage
     * @return Transaction
     */
    public function setProfitPercentage(float $profitPercentage): Transaction
    {
        $this->profitPercentage = $profitPercentage;
        return $this;
    }

    /**
     * @return float
     */
    public function getTotalProfit(): float
    {
        return $this->totalProfit;
    }

    /**
     * @param float $totalProfit
     * @return Transaction
     */
    public function setTotalProfit(float $totalProfit): Transaction
    {
        $this->totalProfit = $totalProfit;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getRequestedAt(): Carbon
    {
        return $this->requestedAt;
    }

    /**
     * @param Carbon $requestedAt
     * @return Transaction
     */
    public function setRequestedAt(Carbon $requestedAt): Transaction
    {
        $this->requestedAt = $requestedAt;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getProcessedAt(): Carbon
    {
        return $this->processedAt;
    }

    /**
     * @param Carbon $processedAt
     * @return Transaction
     */
    public function setProcessedAt(Carbon $processedAt): Transaction
    {
        $this->processedAt = $processedAt;
        return $this;
    }

    /**
     * @return Wallet
     */
    public function getWalletOrigin(): Wallet
    {
        return $this->walletOrigin;
    }

    /**
     * @param Wallet $walletOrigin
     * @return Transaction
     */
    public function setWalletOrigin(Wallet $walletOrigin): Transaction
    {
        $this->walletOrigin = $walletOrigin;
        return $this;
    }

    /**
     * @return Wallet
     */
    public function getWalletDestination(): Wallet
    {
        return $this->walletDestination;
    }

    /**
     * @param Wallet $walletDestination
     * @return Transaction
     */
    public function setWalletDestination(Wallet $walletDestination): Transaction
    {
        $this->walletDestination = $walletDestination;
        return $this;
    }

    /**
     * @return string
     */
    public function getObservation(): string
    {
        return $this->observation;
    }

    /**
     * @param string $observation
     * @return Transaction
     */
    public function setObservation(string $observation): Transaction
    {
        $this->observation = $observation;
        return $this;
    }

    /**
     * @return TransactionStatusEnum
     */
    public function getStatus(): TransactionStatusEnum
    {
        return $this->status;
    }

    /**
     * @param TransactionStatusEnum $status
     * @return Transaction
     */
    public function setStatus(TransactionStatusEnum $status): Transaction
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return TransactionTypeEnum
     */
    public function getType(): TransactionTypeEnum
    {
        return $this->type;
    }

    /**
     * @param TransactionTypeEnum $type
     * @return Transaction
     */
    public function setType(TransactionTypeEnum $type): Transaction
    {
        $this->type = $type;
        return $this;
    }
}
