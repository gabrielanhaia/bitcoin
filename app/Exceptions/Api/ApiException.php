<?php

namespace App\Exceptions\Api;

use App\Exceptions\HttpStatusCodeEnum;
use Throwable;

/**
 * Class ApiException
 * @package App\Exceptions\Api
 *
 * @author Gabriel Anhaia <anhaia.gabriel@gmail.com>
 */
class ApiException extends \Exception
{
    /** @var HttpStatusCodeEnum $httpStatusCode Http status code. */
    protected $httpStatusCode;

    /**
     * ApiException constructor.
     *
     * @param HttpStatusCodeEnum $httpStatusCode
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        HttpStatusCodeEnum $httpStatusCode,
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->httpStatusCode = $httpStatusCode;

        $message = !empty($message) ? $message : $httpStatusCode->getMessage();

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return HttpStatusCodeEnum
     */
    public function getHttpStatusCode(): HttpStatusCodeEnum
    {
        return $this->httpStatusCode;
    }

    /**
     * @param HttpStatusCodeEnum $httpStatusCode
     * @return ApiException
     */
    public function setHttpStatusCode(HttpStatusCodeEnum $httpStatusCode): ApiException
    {
        $this->httpStatusCode = $httpStatusCode;
        return $this;
    }
}
