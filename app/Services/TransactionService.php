<?php


namespace App\Services;

use App\Entities\ExchangeRate;
use App\Entities\Wallet as WalletEntity;
use App\Exceptions\{Api\NotFoundException,
    Api\UnprocessableEntityException,
    Transaction\InsufficientFoundsException,
    Transaction\TransactionAlreadyProcessedException,
    Transaction\TransactionNotFoundException};
use App\Jobs\TransactionProcessor;
use App\Repositories\{CurrencyRepository, SettingRepository, WalletRepository, TransactionRepository, UserRepository};
use Illuminate\Support\{Collection, Facades\Auth};
use App\Entities\Enums\{TransactionStatusEnum, TransactionTypeEnum};
use App\Entities\Transaction as TransactionEntity;
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

    /** @var WalletRepository $walletRepository Repository of wallets. */
    private $walletRepository;

    /** @var UserRepository $userRepository Repository of users. */
    private $userRepository;

    /** @var CurrencyRepository $currencyRepository Repository of currencies. */
    private $currencyRepository;

    /**
     * TransactionService constructor.
     * @param TransactionRepository $transactionRepository
     * @param SettingRepository $settingRepository
     * @param WalletRepository $walletRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        TransactionRepository $transactionRepository,
        SettingRepository $settingRepository,
        WalletRepository $walletRepository,
        UserRepository $userRepository,
        CurrencyRepository $currencyRepository
    )
    {
        $this->transactionRepository = $transactionRepository;
        $this->settingRepository = $settingRepository;
        $this->walletRepository = $walletRepository;
        $this->userRepository = $userRepository;
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * Method responsible for creating a new transaction (Transfer).
     *
     * @param TransactionEntity $transactionEntity Transaction to be created.
     * @throws UnprocessableEntityException
     */
    public function makeTransfer(TransactionEntity $transactionEntity)
    {
        if ($transactionEntity->getWalletOrigin()->getId() === $transactionEntity->getWalletDestination()->getId()) {
            throw new UnprocessableEntityException('You can\'t transfer bitCoins to the same account');
        }


        $profitPercentage = 0;
        $totalProfit = 0;
        $netValue = $transactionEntity->getGrossValue();

        if ($transactionEntity->getWalletOrigin()->getUser()->getId()
            !== $transactionEntity->getWalletDestination()->getUser()->getId()
        ) {
            $settingProfitPercentage = $this->settingRepository->getSetting('profit_transfers');

            if ($settingProfitPercentage !== null) {
                $profitPercentage = $settingProfitPercentage->getValue();
                $totalProfit = ($transactionEntity->getGrossValue() / 100) * $profitPercentage;
                $totalProfit = round($totalProfit);
                $netValue = ($transactionEntity->getGrossValue() - $totalProfit);
            }
        }

        $transactionEntity->setStatus(TransactionStatusEnum::PENDING())
            ->setNetValue($netValue)
            ->setTotalProfit($totalProfit)
            ->setProfitPercentage($profitPercentage)
            ->setType(TransactionTypeEnum::TRANSFER_DEBIT())
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
    public function processTransactionTransfer(TransactionEntity $transactionEntity)
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
            ->setType(TransactionTypeEnum::TRANSFER_CREDIT())
            ->setStatus(TransactionStatusEnum::PROCESSED());

        $this->transactionRepository->createTransaction($transactionDestination);

        $this->transactionRepository->commitTransaction();
    }

    /**
     * Method responsible for credit bitcoins in an account.
     *
     * @param WalletEntity $walletEntity Wallet to insert the bitcoins.
     * @param int $totalBitCoins Amount of bitcoins to be credited.
     * @param null|string $observation Observation for transaction.
     */
    public function creditAmount(WalletEntity $walletEntity, int $totalBitCoins, string $observation = null)
    {
        $lastTotalBalanceWallet = $this->transactionRepository
            ->getLastTotalBalanceByWallet($walletEntity->getId());

        $newTotalBalanceWallet = ($lastTotalBalanceWallet + $totalBitCoins);

        $transaction = new TransactionEntity;
        $transaction->setWallet($walletEntity)
            ->setBalance($newTotalBalanceWallet)
            ->setRequestedAt(Carbon::now())
            ->setProcessedAt(Carbon::now())
            ->setGrossValue($totalBitCoins)
            ->setNetValue($totalBitCoins)
            ->setType(TransactionTypeEnum::CREDIT())
            ->setStatus(TransactionStatusEnum::PROCESSED())
            ->setObservation($observation);

        $this->transactionRepository->createTransaction($transaction);
    }

    /**
     * List all the transactions in a wallet (by wallet address).
     *
     * @param string $walletAddress Address to search the transactions.
     * @return TransactionEntity[]|Collection
     * @throws NotFoundException
     */
    public function listTransactionsByWallet(string $walletAddress)
    {
        $userId = Auth::user()->id;
        $wallet = $this->walletRepository->findWalletByAddress($walletAddress);

        if (empty($wallet)
            || ($wallet->getUser()->getId() !== $userId)
        ) {
            throw new NotFoundException('Wallet not found.');
        }

        $transactions = $this->transactionRepository->listTransactions(
            $wallet->getUser()->getId(),
            $wallet->getId()
        );

        return $transactions;
    }

    /**
     * Return a list of transactions by user.
     *
     * @param int $userId User identifier to search the transactions.
     * @return TransactionEntity[]|Collection
     */
    public function listTransactionsByUser(int $userId)
    {
        $transactions = $this->transactionRepository->listTransactions($userId);

        return $transactions;
    }

    /**
     * Convert an amount of bitcoins to another currency.
     *
     * @param int $totalBitCoins Total bitcoins to be converted.
     * @param string $currencyName Currency name to convert.
     * @return float|null
     */
    public function convertBitCoinsToAnotherCurrency(
        int $totalBitCoins,
        string $currencyName
    ): ?float
    {
        $exchangeRate = $this->currencyRepository
            ->findExchangeRateByExchangeName($currencyName);

        if (empty($exchangeRate) || $exchangeRate->getBitcoinAmount() <= 0) {
            return null;
        }

        $result = ($exchangeRate->getAmount() * $totalBitCoins) / $exchangeRate->getBitcoinAmount();

        return $result;
    }
}
