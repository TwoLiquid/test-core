<?php

namespace App\Microservices\Chat\Responses;

/**
 * Class BaseResponse
 *
 * @property string message
 *
 * @package App\Microservices\Chat\Responses
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