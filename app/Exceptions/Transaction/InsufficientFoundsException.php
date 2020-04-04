<?php


namespace App\Exceptions\Transaction;

use Throwable;

/**
 * Class InsufficientFoundsException
 * @package App\Exceptions\Transaction
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class InsufficientFoundsException extends TransactionException
{
    public function __construct(
        $message = 'Insufficient founds.',
        $code = 0,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
