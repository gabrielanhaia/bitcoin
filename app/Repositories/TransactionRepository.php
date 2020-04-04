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

    }
}
