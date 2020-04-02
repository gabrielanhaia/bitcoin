<?php


namespace App\Entities\Enums;


use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class TransactionStatusEnum
 * @package App\Entities\Enums
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TransactionStatusEnum extends AbstractEnumeration
{
    /** @var string PENDING Status of transactions waiting to be  processed. */
    const PENDING = 'PENDING';

    /** @var string PROCESSED Status of transactions already processed. */
    const PROCESSED = 'PROCESSED';

    /** @var string NOT_PROCESSED Status of transactions not processed for some reason. */
    const NOT_PROCESSED = 'NOT_PROCESSED';
}
