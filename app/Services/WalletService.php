<?php

namespace App\Services;

use App\Entities\Wallet as WalletEntity;
use App\Exceptions\Api\ForbiddenException;
use App\Exceptions\Api\NotFoundException;
use App\Helpers\Facades\TokenFacade;
use App\Repositories\SettingRepository;
use App\Repositories\WalletRepository;
use BitWasp\Bitcoin\{Address\Address,
    Address\AddressCreator,
    Crypto\Random\Random,
    Exceptions\RandomBytesFailure,
    Key\Factory\PrivateKeyFactory,
    Key\KeyToScript\Factory\P2pkhScriptDataFactory};
use Illuminate\Support\Str;

/**
 * Class WalletService
 * @package App\Services
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class WalletService
{
    /** @var TransactionService $transactionService Service of transactions. */
    private $transactionService;

    /** @var WalletRepository $walletRepository Repository of wallets. */
    private $walletRepository;

    /** @var SettingRepository $settingRepository Repository of settings. */
    private $settingRepository;

    /**
     * UserService constructor.
     *
     * @param TransactionService $transactionService Service of transactions.
     * @param WalletRepository $walletRepository Repository of wallets.
     * @param SettingRepository $settingRepository Repository of settings.
     */
    public function __construct(
        TransactionService $transactionService,
        WalletRepository $walletRepository,
        SettingRepository $settingRepository
    )
    {
        $this->walletRepository = $walletRepository;
        $this->settingRepository = $settingRepository;
        $this->transactionService = $transactionService;
    }

    /**
     * Method responsible for creating a new wallet.
     *
     * @param WalletEntity $walletEntity Entity for the wallet to be created.
     * @return WalletEntity
     * @throws ForbiddenException
     * @throws RandomBytesFailure
     * @throws \App\Exceptions\Api\InternalServerErrorException
     */
    public function createWallet(WalletEntity $walletEntity): WalletEntity
    {
        $userId = $walletEntity->getUser()->getId();

        $totalWalletsUser = $this->walletRepository->getTotalWalletsUser($userId);

        $maxWalletsPerUserSetting = $this->settingRepository->getSetting('max_wallets_user');
        $maxWalletsPerUser = $maxWalletsPerUserSetting ? $maxWalletsPerUserSetting->getValue() : 10;

        if ($totalWalletsUser >= $maxWalletsPerUser) {
            throw new ForbiddenException("You can't have more than {$maxWalletsPerUser} wallets.");
        }

        if (empty($walletEntity->getName())) {
            $walletName = 'Wallet ' . TokenFacade::random(6);
            $walletEntity->setName($walletName);
        }

        $address = TokenFacade::generateWalletAddress();
        $walletEntity->setAddress($address);

        $this->walletRepository
            ->createWallet($walletEntity);

        if ($totalWalletsUser === 0) {
            $bonusBitCoinsFirstWallet = $this->settingRepository->getSetting('bonus_bitcoin_first_wallet');
            $totalBitCoins = $bonusBitCoinsFirstWallet ? $bonusBitCoinsFirstWallet->getValue() : 0;
            $totalBitCoins = (int) $totalBitCoins;

            if ($totalBitCoins > 0) {
                $this->transactionService->creditAmount($walletEntity, $totalBitCoins, 'Bonus first wallet.');
            }
        }

        return $walletEntity;
    }

    /**
     * Search for a wallet with its wallet address.
     *
     * @param string $walletAddress Wallet address.
     * @return WalletEntity
     * @throws NotFoundException
     */
    public function findWalletByAddress(string $walletAddress): WalletEntity
    {
        $walletEntity = $this->walletRepository
            ->findWalletByAddress($walletAddress);

        if (empty($walletEntity)) {
            throw new NotFoundException('Wallet not found.');
        }

        return $walletEntity;
    }
}
