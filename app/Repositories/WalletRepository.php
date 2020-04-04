<?php


namespace App\Repositories;

use App\Entities\Wallet as WalletEntity;
use App\Exceptions\Api\InternalServerErrorException;
use App\Models\Wallet;

/**
 * Class WalletRepository for encapsulate the ORM/Data source...
 * In the future it could be MongoDB, External APIs...
 *
 * @package App\Repositories
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class WalletRepository
{
    /** @var Wallet $walletModel Current wallet model. */
    protected $walletModel;

    /**
     * UserRepository constructor.
     * @param Wallet $walletModel Model of wallet (DI).
     */
    public function __construct(Wallet $walletModel)
    {
        $this->walletModel = $walletModel;
    }

    /**
     * Method responsible for creating a new wallet.
     *
     * @param WalletEntity $walletEntity Wallet to be created.
     * @throws InternalServerErrorException
     */
    public function createWallet(WalletEntity $walletEntity)
    {
        $walletCreated = $this->walletModel::create([
            'user_id' => $walletEntity->getUser()->getId(),
            'name' => $walletEntity->getName(),
            'address' => $walletEntity->getAddress()
        ]);

        if (empty($walletCreated)) {
            throw new InternalServerErrorException('Error creating wallet.');
        }

        $walletEntity->setId($walletCreated->id);
    }

    /**
     * Return the number of wallets by user.
     *
     * @param int $userId User identifier to be searched.
     * @return int
     */
    public function getTotalWalletsUser(int $userId): int
    {
        $totalWalletsByUser = $this->walletModel
            ->where('user_id', '=', $userId)
            ->count();

        return (int) $totalWalletsByUser;
    }
}
