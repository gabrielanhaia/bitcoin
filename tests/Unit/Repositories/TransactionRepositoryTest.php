<?php


namespace Tests\Unit\Repositories;

use App\Entities\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use Tests\TestCase;

/**
 * Class TransactionRepositoryTest
 * @package Tests\Unit\Repositories
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TransactionRepositoryTest extends TestCase
{
    /**
     * Method created just to don't duplicate the code
     * (I decided don't user a dataProvider for it).
     *
     * @param int $walletId
     * @return Transaction|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private function getMockTransactionModelGetLastTotalBalanceByWallet(int $walletId)
    {
        $transactionEloquentModelMock = \Mockery::mock(Transaction::class);
        $transactionEloquentModelMock->shouldReceive('select')
            ->once()
            ->with('transactions.balance')
            ->andReturnSelf();

        $transactionEloquentModelMock->shouldReceive('where')
            ->once()
            ->with('transactions.wallet_id', '=', $walletId)
            ->andReturnSelf();

        $transactionEloquentModelMock->shouldReceive('where')
            ->once()
            ->with('transactions.status', '=', TransactionStatusEnum::PROCESSED)
            ->andReturnSelf();

        $transactionEloquentModelMock->shouldReceive('orderBy')
            ->once()
            ->with('transactions.processed_at', 'DESC')
            ->andReturnSelf();

        $transactionEloquentModelMock->shouldReceive('limit')
            ->once()
            ->with(1)
            ->andReturnSelf();

        return $transactionEloquentModelMock;
    }

    /**
     * Test get total balance by a wallet without previous transactions.
     */
    public function testGetLastTotalBalanceByWalletWithoutPreviousTransactions()
    {
        $walletId = 343438728756973232;

        $transactionEloquentModelMock = $this->getMockTransactionModelGetLastTotalBalanceByWallet($walletId);

        $transactionEloquentModelMock->shouldReceive('first')
            ->once()
            ->withNoArgs()
            ->andReturnNull();

        $walletRepositoryMock = \Mockery::mock(WalletRepository::class);
        $transactionRepository = new TransactionRepository($transactionEloquentModelMock, $walletRepositoryMock);

        $resultTotal = $transactionRepository->getLastTotalBalanceByWallet($walletId);

        $this->assertEquals(0, $resultTotal);
    }

    /**
     * Test get total balance by a wallet without previous transactions.
     */
    public function testGetLastTotalBalanceByWallet()
    {
        $walletId = 343438728756973232;
        $balance = 1232312323213;

        $transactionEloquentModelMock = $this->getMockTransactionModelGetLastTotalBalanceByWallet($walletId);
        $transactionEloquentModelMock->shouldReceive('getAttribute')
            ->once()
            ->with('balance')
            ->andReturn($balance);

        $transactionEloquentModelMock->shouldReceive('first')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $walletRepositoryMock = \Mockery::mock(WalletRepository::class);
        $transactionRepository = new TransactionRepository($transactionEloquentModelMock, $walletRepositoryMock);
        $resultTotal = $transactionRepository->getLastTotalBalanceByWallet($walletId);

        $this->assertEquals($balance, $resultTotal);
    }
}
