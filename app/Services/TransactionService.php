<?php


namespace App\Services;

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
    }
}
