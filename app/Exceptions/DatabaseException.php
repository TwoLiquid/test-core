<?php

namespace App\Exceptions;

use Exception;

/**
 * Class DatabaseException
 *
 * @package App\Exceptions
 */
class DatabaseException extends Exception
{
    /**
     * @var string
     */
    protected string $humanReadableMessage;

    /**
     * @var string
     */
    protected string $errorMessage;

    /**
     * @var int
     */
    protected int $errorCode = 500;

    /**
     * DatabaseException constructor
     *
     * @param string $humanReadableMessage
     * @param string $errorMessage
     */
    public function __construct(
        string $humanReadableMessage,
        string $errorMessage
    )
    {
        $this->humanReadableMessage = $humanReadableMessage;
        $this->errorMessage = $errorMessage;

        parent::__construct(
            $errorMessage,
            $this->errorCode,
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
