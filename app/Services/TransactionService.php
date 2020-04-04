<?php


namespace App\Services;

use App\Entities\Transaction as TransactionEntity;
use App\Repositories\TransactionRepository;

/**
 * Class TransactionService
 * @package App\Services
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TransactionService
{
    /** @var TransactionRepository $transactionRepository Repository of transactions. */
    private $transactionRepository;

    /**
     * TransactionService constructor.
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Method responsible for creating a new transaction.
     *
     * @param TransactionEntity $transactionEntity Transaction to be created.
     */
    public function createTransaction(TransactionEntity $transactionEntity)
    {
        $this->transactionRepository->createTransaction($transactionEntity);
    }
}
