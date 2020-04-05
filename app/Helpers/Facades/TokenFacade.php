<?php


namespace App\Helpers\Facades;

use BitWasp\Bitcoin\Address\PayToPubKeyHashAddress;
use Illuminate\Support\Facades\Facade;

/**
 * Class TokenFacade
 * @package App\Helpers\Facades
 *
 * @method static string generateWalletAddress()
 * @method static string random(string $length)
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class TokenFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'token_helper';
    }
}
