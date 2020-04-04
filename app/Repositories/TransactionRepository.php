<?php


namespace App\Repositories;

use App\Entities\Transaction as TransactionEntity;
use App\Models\Transaction;

/**
 * Class TransactionRepository
 * @package App\Repositories
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TransactionRepository
{
    /** @var Transaction $transactionModel Model os transactions (eloquent model). */
    private $transactionModel;

    /**
     * TransactionRepository constructor.
     * @param Transaction $transactionModel
     */
    public function __construct(Transaction $transactionModel)
    {
        $this->transactionModel = $transactionModel;
    }

    /**
     * Method responsible for creating a new transaction.
     *
     * @param TransactionEntity $transactionEntity Transaction to be created.
     */
    public function createTransaction(TransactionEntity $transactionEntity)
    {
        $transactionCreated = $this->transactionModel::create([
            'wallet_id' => $transactionEntity->getWallet()->getId(),
            'type' => $transactionEntity->getType()->value(),
            'status' => $transactionEntity->getStatus()->value(),
            'gross_value' => $transactionEntity->getGrossValue(),
            'net_value' => $transactionEntity->getNetValue(),
            'profit_percentage' => $transactionEntity->getProfitPercentage(),
            'total_profit' => $transactionEntity->getTotalProfit(),
            'requested_at' => $transactionEntity->getRequestedAt(),
            'wallet_id_origin' => $transactionEntity->getWalletOrigin()->getId(),
            'wallet_id_destination' => $transactionEntity->getWalletDestination()->getId()
        ]);

        $transactionEntity->setId($transactionCreated->id);
    }
}
