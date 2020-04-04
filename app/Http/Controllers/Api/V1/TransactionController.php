<?php

namespace App\Http\Controllers\Api\V1;

use App\Entities\Enums\TransactionTypeEnum;
use App\Entities\Transaction as TransactionEntity;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnauthorizedException;
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
     * @throws NotFoundException
     */
    public function makeTransfer(CreateTransactionRequest $request, Auth $auth): TransactionResource
    {
        $wallet = $this->walletService->findWalletByAddress($request->post('address'));
        $walletDestination = $this->walletService->findWalletByAddress($request->post('address_destination'));
        $totalTransaction = $request->post('total_transaction');

        $userId = $auth::user()->id;
        if ($userId !== $wallet->getUser()->getId()) {
            throw new NotFoundException('Wallet not found.');
        }

        $transactionEntity = new TransactionEntity;
        $transactionEntity->setWallet($wallet)
            ->setType(TransactionTypeEnum::TRANSFER_DEBIT())
            ->setWalletOrigin($wallet)
            ->setWalletDestination($walletDestination)
            ->setGrossValue($totalTransaction);

        $this->transactionService->makeTransfer($transactionEntity);

        return new TransactionResource($transactionEntity);
    }
}
