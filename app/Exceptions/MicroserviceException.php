<?php

namespace App\Exceptions;

use Exception;

/**
 * Class MicroserviceException
 *
 * @package App\Exceptions
 */
class MicroserviceException extends Exception
{
    /**
     * @var string
     */
    protected string $appearance;

    /**
     * @var string
     */
    protected string $humanReadableMessage;

    /**
     * @var string
     */
    protected string $errorMessage;

    /**
     * @var array|null
     */
    protected ?array $validationErrors;

    /**
     * @var int
     */
    protected int $errorCode;

    /**
     * MicroserviceException constructor
     *
     * @param string $appearance
     * @param string $humanReadableMessage
     * @param string $errorMessage
     * @param array|null $validationErrors
     * @param int $errorCode
     */
    public function __construct(
        string $appearance,
        string $humanReadableMessage,
        string $errorMessage,
        ?array $validationErrors,
        int $errorCode
    )
    {
        $this->appearance = $appearance;
        $this->humanReadableMessage = $humanReadableMessage;
        $this->errorMessage = $errorMessage;
        $this->validationErrors = $validationErrors;
        $this->errorCode = $errorCode;

        parent::__construct(
            $errorMessage,
            $errorCode
        );
    }

    /**
     * @return string
     */
    public function getAppearance() : string
    {
        return $this->appearance;
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
     * @return array|null
     */
    public function getValidationErrors() : ?array
    {
        return $this->validationErrors;
    }

    /**
     * @return int
     */
    public function getErrorCode() : int
    {
        return $this->errorCode;
    }
}