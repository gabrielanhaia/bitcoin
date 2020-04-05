<?php

namespace App\Services;

use App\Entities\Wallet as WalletEntity;
use App\Exceptions\Api\ForbiddenException;
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

    /** @var SettingRepository $settingRepository Repository of settings.  */
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


        if ($totalWalletsUser === 10) {
            throw new ForbiddenException('You can\'t have more than 10 wallets.');
        }

        if (empty($walletEntity->getName())) {
            $walletName = 'Wallet ' . Str::random(6);
            $walletEntity->setName($walletName);
        }

        $address = $this->generateWalletAddress();
        $walletEntity->setAddress($address->getAddress());

        $this->walletRepository
            ->createWallet($walletEntity);

        if ($totalWalletsUser === 0) {
            $totalBitCoins = 100000000;
            $this->transactionService->creditAmount($walletEntity, $totalBitCoins);
        }

        return $walletEntity;
    }

    /**
     * Generate a address.
     *
     * @return Address
     * @throws RandomBytesFailure
     */
    private function generateWalletAddress()
    {
        $privateKeyFactory = new PrivateKeyFactory;
        $privateKey = $privateKeyFactory->generateCompressed(new Random());
        $publicKey = $privateKey->getPublicKey();
        $addrCreator = new AddressCreator();
        $factory = new P2pkhScriptDataFactory();
        $scriptPubKey = $factory->convertKey($publicKey)->getScriptPubKey();
        $address = $addrCreator->fromOutputScript($scriptPubKey);

        return $address;
    }

    /**
     * Search for a wallet with its wallet address.
     *
     * @param string $walletAddress Wallet address.
     * @return WalletEntity
     */
    public function findWalletByAddress(string $walletAddress): WalletEntity
    {
        $walletEntity = $this->walletRepository
            ->findWalletByAddress($walletAddress);

        return $walletEntity;
    }
}
