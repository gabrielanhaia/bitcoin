<?php


namespace App\Entities\Enums;

/**
 * Class TransactionTypeEnum
 * @package App\Entities\Enums
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TransactionTypeEnum
{
    /** @var string TRANSFER Status of transactions for transfers between accounts. */
    const TRANSFER = 'TRANSFER';

    /** @var string DEBIT Status of transactions for debit (cash money). */
    const DEBIT = 'DEBIT';

    /** @var string CREDIT Status of transactions for credit (put money). */
    const CREDIT = 'CREDIT';
}
