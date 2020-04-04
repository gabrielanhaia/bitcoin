<?php

namespace App\Http\Controllers\Api\V1;

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

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Method responsible for creating a new wallet.
     *
     * @param Auth $auth
     * @param CreateWalletRequest $request
     *
     * @return WalletResource
     */
    public function create(Auth $auth, CreateWalletRequest $request)
    {
        $walletName = $request->get('name', '');
        $user = new User($auth::id());

        $wallet = new Wallet;
        $wallet->setName($walletName)
            ->setUser($user);

        $this->walletService->createWallet($wallet);

        return new WalletResource($wallet);
    }
}
