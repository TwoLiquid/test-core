<?php

namespace App\Microservices\Chat\Responses;

/**
 * Class ChatResponse
 *
 * @property int $id
 *
 * @package App\Microservices\Chat\Responses
 */
class ChatResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * ChatResponse constructor
     *
     * @param object $response
     * @param string $message
     */
    public function __construct(
        object $response,
        string $message
    )
    {
        $this->id = $response->id;

        parent::__construct($message);
    }
}