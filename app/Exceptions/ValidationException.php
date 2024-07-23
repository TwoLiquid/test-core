<?php

namespace App\Exceptions;

use Exception;

/**
 * Class ValidationException
 *
 * @package App\Exceptions
 */
class ValidationException extends Exception
{
    /**
     * @var string
     */
    protected string $errorMessage;

    /**
     * @var string 
     */
    protected string $errorKey;

    /**
     * @var int
     */
    protected int $errorCode = 400;

    /**
     * ValidationException constructor
     *
     * @param string $errorMessage
     * @param string $errorKey
     */
    public function __construct(
        string $errorMessage,
        string $errorKey
    )
    {
        $this->errorMessage = $errorMessage;
        $this->errorKey = $errorKey;

        parent::__construct(
            $errorMessage,
            $this->errorCode
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
     * @return string
     */
    public function getErrorKey() : string
    {
        return $this->errorKey;
    }

    /**
     * @return int
     */
    public function getErrorCode() : int
    {
        return $this->errorCode;
    }
}