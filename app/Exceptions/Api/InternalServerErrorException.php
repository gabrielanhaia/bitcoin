<?php

namespace App\Exceptions\Api;

use App\Exceptions\HttpStatusCodeEnum;
use Throwable;

/**
 * Class InternalServerErrorException
 * @package App\Exceptions\Api
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class InternalServerErrorException extends ApiException
{
    /**
     * InternalServerErrorException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(HttpStatusCodeEnum::INTERNAL_SERVER_ERROR(), $message, $code, $previous);
    }
}
