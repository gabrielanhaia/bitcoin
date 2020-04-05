<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\Api\ForbiddenException;
use App\Exceptions\Api\InternalServerErrorException;
use App\Exceptions\Api\NotFoundException;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
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

    /** @var TransactionService $transactionService Service of transactions. */
    private $transactionService;

    /**
     * WalletController constructor.
     *
     * @param WalletService $walletService
     * @param TransactionService $transactionService
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        WalletService $walletService,
        TransactionService $transactionService,
        TransactionRepository $transactionRepository
    )
    {
        $this->walletService = $walletService;
        $this->transactionRepository = $transactionRepository;
        $this->transactionService = $transactionService;
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

        $lastTotalBalance = $this->transactionRepository->getLastTotalBalanceByWallet($wallet->getId());

        $extraInformation = [
            'balance_bitcoin' => $lastTotalBalance,
            'balance_dollar' => $this->transactionService
                ->convertBitCoinsToAnotherCurrency($lastTotalBalance, 'USD'),
            'balance_euro' => $this->transactionService
                ->convertBitCoinsToAnotherCurrency($lastTotalBalance, 'EUR'),
            'balance_brazilian_real' =>  $this->transactionService
                ->convertBitCoinsToAnotherCurrency($lastTotalBalance, 'BRL')
        ];

        return new WalletResource($wallet, $extraInformation);
    }
}
