<?php


namespace App\Entities\Enums;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class TransactionTypeEnum
 * @package App\Entities\Enums
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TransactionTypeEnum extends AbstractEnumeration
{
    /** @var string TRANSFER_CREDIT Status of transactions for transfers between accounts (credit). */
    const TRANSFER_CREDIT = 'TRANSFER_CREDIT';

    /** @var string TRANSFER_DEBIT Status of transactions for transfers between accounts (debit). */
    const TRANSFER_DEBIT = 'TRANSFER_DEBIT';

    /** @var string DEBIT Status of transactions for debit (cash money). */
    const DEBIT = 'DEBIT';

    /** @var string CREDIT Status of transactions for credit (put money). */
    const CREDIT = 'CREDIT';
}
