<?php


namespace App\Exceptions\Transaction;

use Throwable;

/**
 * Class TransactionNotFoundException
 * @package App\Exceptions\Transaction
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TransactionNotFoundException extends TransactionException
{
    public function __construct(
        $message = 'Transaction not found.',
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
