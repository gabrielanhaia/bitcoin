<?php

namespace App\Jobs;

use App\Entities\Transaction as TransactionEntity;
use App\Services\TransactionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class TransactionProcessor
 * @package App\Jobs
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TransactionProcessor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var TransactionEntity $transactionEntity Transaction to be processed. */
    private $transactionEntity;

    /**
     * Create a new job instance.
     *
     * @param TransactionEntity $transactionEntity Transaction to be processed.
     */
    public function __construct(TransactionEntity $transactionEntity)
    {
        $this->transactionEntity = $transactionEntity;
    }

    /**
     * Execute the job.
     *
     * @param TransactionService $transactionService (injected by DI).
     * @return void
     * @throws \App\Exceptions\Transaction\InsufficientFoundsException
     * @throws \App\Exceptions\Transaction\TransactionAlreadyProcessedException
     * @throws \App\Exceptions\Transaction\TransactionNotFoundException
     */
    public function handle(TransactionService $transactionService)
    {
        $transactionService->processTransactionTransfer($this->transactionEntity);
    }
}
