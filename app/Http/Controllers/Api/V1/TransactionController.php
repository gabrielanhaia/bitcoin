<?php

namespace App\Http\Controllers\Api\V1;

use App\Entities\Transaction as TransactionEntity;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateTransactionRequest;
use App\Http\Resources\V1\Transaction as TransactionResource;
use App\Services\TransactionService;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;

/**
 * Class TransactionController
 * @package App\Http\Controllers\Api\V1
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TransactionController extends Controller
{
    /** @var TransactionService $transactionService Service of transactions. */
    private $transactionService;

    /** @var WalletService $walletService Service of wallets. */
    private $walletService;

    /**
     * TransactionController constructor.
     *
     * @param TransactionService $transactionService
     * @param WalletService $walletService
     */
    public function __construct(
        TransactionService $transactionService,
        WalletService $walletService
    )
    {
        $this->transactionService = $transactionService;
        $this->walletService = $walletService;
    }

    /**
     * Method responsible for creating a new transaction.
     *
     * @param CreateTransactionRequest $request
     * @param Auth $auth
     * @return TransactionResource
     */
    public function create(CreateTransactionRequest $request, Auth $auth): TransactionResource
    {
        $userId = $auth::user()->id;
        $wallet = $this->walletService->findWalletByAddress($request->get('address'));

        $transactionEntity = new TransactionEntity;
        $transactionEntity->setWallet($wallet);

        return new TransactionResource($transactionEntity);
    }
}
