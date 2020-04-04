<?php

namespace App\Exceptions;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * Class HttpStatusCodeEnum
 * @package App\Enums
 *
 * @method static $this OK()
 * @method static $this ACCEPTED()
 * @method static $this NO_CONTENT()
 * @method static $this NOT_FOUND()
 * @method static $this CREATED()
 * @method static $this UNAUTHORIZED()
 * @method static $this CONFLICT()
 * @method static $this UNPROCESSABLE_ENTITY()
 * @method static $this INTERNAL_SERVER_ERROR()
 * @method static $this METHOD_NOT_ALLOWED()
 * @method static $this GONE()
 * @method static $this NOT_IMPLEMENTED()
 * @method static $this LOCKED()
 *
 * @author Gabriel Anhaia <anhaia.gabrielgabriel@gmail.com>
 */
class HttpStatusCodeEnum extends AbstractEnumeration
{
    /** @var int OK */
    const OK = 200;

    /** @var int CREATED */
    const CREATED = 201;

    /** @var int ACCEPTED */
    const ACCEPTED = 202;

    /** @var int NO_CONTENT */
    const NO_CONTENT = 204;

    /** @var int BAD_REQUEST */
    const BAD_REQUEST = 400;

    /** @var int UNAUTHORIZED */
    const UNAUTHORIZED = 401;

    /** @var int FORBIDDEN */
    const FORBIDDEN = 403;

    /** @var int NOT_FOUND */
    const NOT_FOUND = 404;

    /** @var int METHOD_NOT_ALLOWED */
    const METHOD_NOT_ALLOWED = 405;

    /** @var int CONFLICT */
    const CONFLICT = 409;

    /** @var int GONE */
    const GONE = 410;

    /** @var int UNPROCESSABLE_ENTITY */
    const UNPROCESSABLE_ENTITY = 422;

    /** @var int LOCKED */
    const LOCKED = 423;

    /** @var int INTERNAL_SERVER_ERROR */
    const INTERNAL_SERVER_ERROR = 500;

    /** @var int NOT_IMPLEMENTED */
    const NOT_IMPLEMENTED = 501;

    /** @var array $httpMessages Http Error messages. */
    private $httpMessages = [
        self::NOT_FOUND => 'Not found.',
        self::ACCEPTED => 'Accepted.',
        self::NO_CONTENT => 'No content.',
        self::CREATED => 'Created.',
        self::BAD_REQUEST => 'Bad request.',
        self::UNAUTHORIZED => 'Unauthorized.',
        self::OK => 'Ok.',
        self::UNPROCESSABLE_ENTITY => 'Unprocessable entity.',
        self::CONFLICT => 'Conflict.',
        self::INTERNAL_SERVER_ERROR => 'Internal server error.',
        self::METHOD_NOT_ALLOWED => 'Method not allowed.',
        self::LOCKED => 'Locked.',
        self::GONE => 'Gone.',
        self::NOT_IMPLEMENTED => 'Not implemented.',
        self::FORBIDDEN => 'Forbidden.'
    ];

    /**
     * Return de error message.
     * @return mixed
     */
    public function getMessage() : string
    {
        return $this->httpMessages[$this->value()];
    }
}
