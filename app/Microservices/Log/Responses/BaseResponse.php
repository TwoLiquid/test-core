<?php

namespace App\Microservices\Log\Responses;

/**
 * Class BaseResponse
 *
 * @property string message
 *
 * @package App\Microservices\Log\Responses
 */
class BaseResponse
{
    /**
     * @var string|null
     */
    public ?string $message;

    /**
     * BaseResponse constructor
     *
     * @param string|null $message
     */
    public function __construct(
        ?string $message
    )
    {
        $this->message = $message;
    }
}