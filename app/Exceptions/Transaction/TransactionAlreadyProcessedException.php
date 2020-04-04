<?php


namespace App\Exceptions\Transaction;

use Throwable;

/**
 * Class TransactionAlreadyProcessedException
 * @package App\Exceptions\Transaction
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TransactionAlreadyProcessedException extends TransactionException
{
    public function __construct(
        $message = 'Transaction already processed.',
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
