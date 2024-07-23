<?php

namespace App\Exceptions;

use Exception;

/**
 * Class LogException
 *
 * @package App\Exceptions
 */
class LogException extends Exception
{
    /**
     * @var string
     */
    protected string $errorMessage;

    /**
     * @var int
     */
    protected int $errorCode = 500;

    /**
     * LogException constructor
     *
     * @param string $errorMessage
     */
    public function __construct(
        string $errorMessage
    )
    {
        $this->errorMessage = $errorMessage;

        parent::__construct(
            $errorMessage,
            $this->errorCode,
        );
    }

    /**
     * @return string
     */
    public function getErrorMessage() : string
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