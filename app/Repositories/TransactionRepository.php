<?php


namespace App\Repositories;

use App\Entities\Enums\TransactionStatusEnum;
use App\Entities\Enums\TransactionTypeEnum;
use App\Entities\Transaction as TransactionEntity;
use App\Models\Transaction;
use Carbon\Carbon;

/**
 * Class TransactionRepository
 * @package App\Repositories
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TransactionRepository extends Repository
{
    /** @var Transaction $transactionModel Model os transactions (eloquent model). */
    private $transactionModel;

    /** @var WalletRepository $walletRepository Repository of wallets. */
    private $walletRepository;

    /**
     * TransactionRepository constructor.
     * @param Transaction $transactionModel
     * @param WalletRepository $walletRepository
     */
    public function __construct(Transaction $transactionModel, WalletRepository $walletRepository)
    {
        $this->transactionModel = $transactionModel;
        $this->walletRepository = $walletRepository;
    }

    /**
     * Method responsible for creating a new transaction.
     *
     * @param TransactionEntity $transactionEntity Transaction to be created.
     */
    public function createTransaction(TransactionEntity $transactionEntity)
    {
        $transactionData = [
            'wallet_id' => $transactionEntity->getWallet()->getId(),
            'type' => $transactionEntity->getType()->value(),
            'status' => $transactionEntity->getStatus()->value(),
            'gross_value' => $transactionEntity->getGrossValue(),
            'net_value' => $transactionEntity->getNetValue(),
            'profit_percentage' => $transactionEntity->getProfitPercentage(),
            'total_profit' => $transactionEntity->getTotalProfit(),
            'requested_at' => $transactionEntity->getRequestedAt(),
            'wallet_id_origin' => $transactionEntity->getWalletOrigin()
                ? $transactionEntity->getWalletOrigin()->getId()
                : null,
            'wallet_id_destination' => $transactionEntity->getWalletDestination()
                ? $transactionEntity->getWalletDestination()->getId()
                : null
        ];

        if ($transactionEntity->getBalance() !== null) {
            $transactionData['balance'] = $transactionEntity->getBalance();
        }

        if (!empty($transactionEntity->getProcessedAt())) {
            $transactionData['processed_at'] = $transactionEntity->getProcessedAt();
        }

        $transactionCreated = $this->transactionModel::create($transactionData);

        $transactionEntity->setId($transactionCreated->id);
    }

    /**
     * Return the last total balance in the transactions.
     *
     * @param int $walletId
     * @return int
     */
    public function getLastTotalBalanceByWallet(int $walletId): int
    {
        $lastTransactionBalanceResult = $this->transactionModel
            ->select('transactions.balance')
            ->where('transactions.wallet_id', '=', $walletId)
            ->where('transactions.status', '=', TransactionStatusEnum::PROCESSED)
            ->orderBy('transactions.processed_at', 'DESC')
            ->limit(1)
            ->first();

        $lastTransactionBalance = $lastTransactionBalanceResult
            ? $lastTransactionBalanceResult->balance
            : 0;

        return (int)$lastTransactionBalance;
    }

    /**
     * Method responsible for search a transaction in the Database/Data source.
     *
     * @param int $transactionId Transaction identifier to be searched.
     * @return TransactionEntity|null
     */
    public function find(int $transactionId): ?TransactionEntity
    {
        $transactionResponse = $this->transactionModel->find($transactionId);

        if (empty($transactionResponse)) {
            return null;
        }

        $wallet = $this->walletRepository->find($transactionResponse->wallet_id);
        $walletOrigin = $this->walletRepository->find($transactionResponse->wallet_id_origin);
        $walletDestination = $this->walletRepository->find($transactionResponse->wallet_id_destination);

        $transactionEntity = new TransactionEntity($transactionResponse->id);
        $transactionEntity->setWallet($wallet)
            ->setWalletOrigin($walletOrigin)
            ->setWalletDestination($walletDestination)
            ->setType(TransactionTypeEnum::memberByValue($transactionResponse->type))
            ->setStatus(TransactionStatusEnum::memberByValue($transactionResponse->status))
            ->setBalance($transactionResponse->balance)
            ->setGrossValue($transactionResponse->gross_value)
            ->setNetValue($transactionResponse->net_value)
            ->setProfitPercentage($transactionResponse->profit_percentage)
            ->setTotalProfit($transactionResponse->total_profit)
            ->setRequestedAt(Carbon::createFromFormat('Y-m-d H:i:s', $transactionResponse->requested_at))
            ->setObservation($transactionResponse->observation);

        if (!empty($transactionResponse->processed_at)) {
            $transactionEntity->setProcessedAt(Carbon::createFromFormat('Y-m-d H:i:s', $transactionResponse->processed_at));
        }

        return $transactionEntity;
    }

    /**
     * Method responsible for updating a transaction.
     *
     * @param TransactionEntity $transactionEntity Transaction to be updated.
     *
     * TODO: Improve the updates (fields not filled).
     */
    public function updateTransaction(TransactionEntity $transactionEntity)
    {
        $this->transactionModel::where('id', '=', $transactionEntity->getId())
            ->update([
                'status' => $transactionEntity->getStatus()->value(),
                'balance' => $transactionEntity->getBalance(),
                'processed_at' => $transactionEntity->getProcessedAt(),
                'observation' => $transactionEntity->getObservation()
            ]);
    }

    /**
     * @param int $transactionId Transaction identifier to be updated.
     * @param TransactionStatusEnum $transactionStatusEnum New transaction status.
     * @param string $observation Observations to be updated.
     */
    public function updateTransactionStatus(
        int $transactionId,
        TransactionStatusEnum $transactionStatusEnum,
        string $observation = null
    )
    {
        $transactionResult = $this->transactionModel->find($transactionId);

        $transactionResult->status = $transactionStatusEnum->value();

        if (!empty($observation)) {
            $transactionResult->observation = $observation;
        }

        $transactionResult->save();
    }
}
