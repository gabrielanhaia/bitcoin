<?php


namespace App\Services;

use App\Entities\Enums\TransactionStatusEnum;
use App\Entities\Enums\TransactionTypeEnum;
use App\Entities\Transaction as TransactionEntity;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;

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
     * Method responsible for creating a new transaction (Transfer).
     *
     * @param TransactionEntity $transactionEntity Transaction to be created.
     */
    public function makeTransfer(TransactionEntity $transactionEntity)
    {
        $profitPercentage = 0;
        $totalProfit = 0;
        $netValue = $transactionEntity->getGrossValue();

        if ($transactionEntity->getWalletOrigin()->getUser()->getId()
            || $transactionEntity->getWalletDestination()->getUser()->getId()) {
            // TODO: Change to load from settings.
            $profitPercentage = 1.5;
            $totalProfit = ($transactionEntity->getGrossValue() / 100) * $profitPercentage;
        }

        $transactionEntity->setStatus(TransactionStatusEnum::PENDING())
            ->setNetValue($netValue)
            ->setTotalProfit($totalProfit)
            ->setProfitPercentage($profitPercentage)
            ->setType(TransactionTypeEnum::TRANSFER())
            ->setRequestedAt(Carbon::now());

        $this->transactionRepository->createTransaction($transactionEntity);
    }
}
