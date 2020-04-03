<?php

namespace App\Exceptions\Api;

use App\Exceptions\HttpStatusCodeEnum;
use Throwable;

/**
 * Class ConflictException
 * @package App\Exceptions\Api
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class ConflictException extends ApiException
{
    /**
     * ConflictException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(HttpStatusCodeEnum::CONFLICT(), $message, $code, $previous);
    }
}
