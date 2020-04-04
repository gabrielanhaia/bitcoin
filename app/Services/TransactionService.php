<?php


namespace App\Services;

use App\Exceptions\Transaction\InsufficientFoundsException;
use App\Exceptions\Transaction\TransactionAlreadyProcessedException;
use App\Exceptions\Transaction\TransactionNotFoundException;
use App\Jobs\TransactionProcessor;
use App\Repositories\SettingRepository;
use App\Entities\Enums\{TransactionStatusEnum, TransactionTypeEnum};
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

    /** @var SettingRepository $settingRepository Repository of settings. */
    private $settingRepository;

    /**
     * TransactionService constructor.
     * @param TransactionRepository $transactionRepository
     * @param SettingRepository $settingRepository
     */
    public function __construct(
        TransactionRepository $transactionRepository,
        SettingRepository $settingRepository
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->settingRepository = $settingRepository;
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

            $settingProfitPercentage = $this->settingRepository->getSetting('profit_transfers');

            if ($settingProfitPercentage !== null) {
                $profitPercentage = $settingProfitPercentage->getValue();
                $totalProfit = ($transactionEntity->getGrossValue() / 100) * $profitPercentage;
                $totalProfit = round($totalProfit);
                $netValue = $transactionEntity->getGrossValue() - $totalProfit;
            }
        }

        $transactionEntity->setStatus(TransactionStatusEnum::PENDING())
            ->setNetValue($netValue)
            ->setTotalProfit($totalProfit)
            ->setProfitPercentage($profitPercentage)
            ->setType(TransactionTypeEnum::TRANSFER())
            ->setRequestedAt(Carbon::now());

        $this->transactionRepository->createTransaction($transactionEntity);

        /**
         * TODO: In production it would be 'dispatch' to use a queue with redis (It's on this way only for the test).
         *
         * When it happens the exceptions would never stop the request. They would be logged and after the user
         * would see the transaction error (transaction statement) in an app or web interface (maybe).
         * The errors will be logged anyway (So the developers can check it later).
         *
         * (Observation: I would never put this comment in a real application, please check the notes send with the
         * project)
         */
        dispatch_now(new TransactionProcessor($transactionEntity));
    }

    /**
     * Method responsible for processing a transaction.
     *
     * @param TransactionEntity $transactionEntity Transaction to be processed.
     * @throws TransactionNotFoundException Exception throw where transaction is not found.
     * @throws TransactionAlreadyProcessedException Exception throw where transaction had been already processed.
     * @throws InsufficientFoundsException Exception throw where there isn't enough bitcoins in this wallet.
     */
    public function processTransaction(TransactionEntity $transactionEntity)
    {
        $currentStateTransaction = $this->transactionRepository->find($transactionEntity->getId());

        if (empty($currentStateTransaction)) {
            throw new TransactionNotFoundException;
        }

        if ($currentStateTransaction->getStatus()->value() === TransactionStatusEnum::PROCESSED) {
            throw new TransactionAlreadyProcessedException;
        }

        $this->transactionRepository->beginTransaction();

        $lastTotalBalanceWalletOrigin = $this->transactionRepository
            ->getLastTotalBalanceByWallet($transactionEntity->getWalletOrigin()->getId());

        if ($lastTotalBalanceWalletOrigin < $transactionEntity->getGrossValue()) {
            $transactionEntity->setBalance($lastTotalBalanceWalletOrigin)
                ->setStatus(TransactionStatusEnum::NOT_PROCESSED())
                ->setProcessedAt(Carbon::now())
                ->setObservation("Insufficient founds. Current ammout '{$lastTotalBalanceWalletOrigin}'");

            $this->transactionRepository->updateTransaction($transactionEntity);
            $this->transactionRepository->commitTransaction();

            throw new InsufficientFoundsException;
        }

        $newTotalBalanceWalletOrigin = $lastTotalBalanceWalletOrigin - $currentStateTransaction->getGrossValue();
        $transactionEntity->setBalance($newTotalBalanceWalletOrigin)
            ->setStatus(TransactionStatusEnum::PROCESSED())
            ->setProcessedAt(Carbon::now());
        $this->transactionRepository->updateTransaction($transactionEntity);

        $lastTotalBalanceWalletDestination = $this->transactionRepository
            ->getLastTotalBalanceByWallet($transactionEntity->getWalletDestination()->getId());
        $newTotalBalanceWalletDestination = $lastTotalBalanceWalletDestination + $currentStateTransaction->getNetValue();

        $transactionDestination = new TransactionEntity;
        $transactionDestination->setWallet($transactionEntity->getWalletDestination())
            ->setWalletOrigin($transactionEntity->getWalletOrigin())
            ->setWalletDestination($transactionEntity->getWalletDestination())
            ->setBalance($newTotalBalanceWalletDestination)
            ->setRequestedAt(Carbon::now())
            ->setProcessedAt(Carbon::now())
            ->setGrossValue($currentStateTransaction->getNetValue())
            ->setNetValue($currentStateTransaction->getNetValue())
            ->setType(TransactionTypeEnum::TRANSFER())
            ->setStatus(TransactionStatusEnum::PROCESSED());

        $this->transactionRepository->createTransaction($transactionDestination);

        $this->transactionRepository->commitTransaction();
    }
}
