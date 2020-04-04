<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\Api\ForbiddenException;
use App\Exceptions\Api\InternalServerErrorException;
use App\Exceptions\Api\NotFoundException;
use App\Repositories\TransactionRepository;
use BitWasp\Bitcoin\Exceptions\RandomBytesFailure;
use App\Entities\{Wallet, User};
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateWalletRequest;
use App\Http\Resources\V1\Wallet as WalletResource;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;

/**
 * Class WalletController
 * @package App\Http\Controllers\Api\V1
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class WalletController extends Controller
{
    /** @var WalletService $walletService Service of wallets. */
    private $walletService;

    /** @var TransactionRepository $transactionRepository Repository of transactions. */
    private $transactionRepository;

    /**
     * WalletController constructor.
     *
     * @param WalletService $walletService
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(WalletService $walletService, TransactionRepository $transactionRepository)
    {
        $this->walletService = $walletService;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Method responsible for creating a new wallet.
     *
     * @param Auth $auth
     * @param CreateWalletRequest $request
     *
     * @return WalletResource
     * @throws ForbiddenException
     * @throws InternalServerErrorException
     * @throws RandomBytesFailure
     */
    public function createWallet(Auth $auth, CreateWalletRequest $request)
    {
        $walletName = $request->get('name', '');
        $user = new User($auth::id());

        $wallet = new Wallet;
        $wallet->setName($walletName)
            ->setUser($user);

        $this->walletService->createWallet($wallet);

        return new WalletResource($wallet);
    }

    /**
     * Method responsible for return a wallet.
     *
     * @param string $walletAddress
     * @return WalletResource
     * @throws NotFoundException
     */
    public function getWallet(string $walletAddress)
    {
        $wallet = $this->walletService->findWalletByAddress($walletAddress);

        if (empty($wallet)) {
            throw new NotFoundException('Wallet not found.');
        }

        $extraInformation = [
            'balance_bitcoin' => $this->transactionRepository->getLastTotalBalanceByWallet($wallet->getId())
        ];

        return new WalletResource($wallet, $extraInformation);
    }
}
