<?php


namespace App\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class TokenFacade
 * @package App\Helpers\Facades
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
