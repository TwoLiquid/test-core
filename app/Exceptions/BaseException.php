<?php

namespace App\Exceptions;

use Exception;

/**
 * Class BaseException
 *
 * @package App\Exceptions
 */
class BaseException extends Exception
{
    /**
     * @var string
     */
    protected string $humanReadableMessage;

    /**
     * @var string|null
     */
    protected ?string $errorMessage;

    /**
     * @var int
     */
    protected int $errorCode;

    /**
     * BaseException constructor
     *
     * @param string $humanReadableMessage
     * @param string|null $errorMessage
     * @param int $errorCode
     */
    public function __construct(
        string $humanReadableMessage,
        ?string $errorMessage,
        int $errorCode
    )
    {
        $this->humanReadableMessage = $humanReadableMessage;
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;

        parent::__construct(
            $errorMessage,
            $errorCode
        );
    }

    /**
     * @return string
     */
    public function getHumanReadableMessage() : string
    {
        return $this->humanReadableMessage;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage() : ?string
    {
        return $this->errorMessage;
    }

    /**
     * @return int
     */
    public function getErrorCode() : int
    {
        return $this->errorCode;
    }
}