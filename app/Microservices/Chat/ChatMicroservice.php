<?php

namespace App\Microservices\Chat;

use App\Exceptions\DatabaseException;
use App\Microservices\Chat\Traits\ChatTrait;
use App\Microservices\Chat\Traits\WordTrait;
use App\Microservices\ExceptionTrait;
use App\Services\Auth\AuthService;
use GuzzleHttp\Client;

/**
 * Class ChatMicroservice
 *
 * @package App\Microservices\Chat
 */
class ChatMicroservice
{
    use ExceptionTrait, ChatTrait, WordTrait;

    /**
     * Appearance constant
     */
    const APPEARANCE = 'chat';

    /**
     * Chat microservice hosting url
     *
     * @var string
     */
    protected string $apiUrl;

    /**
     * Request headers
     *
     * @var array
     */
    protected array $headers;

    /**
     * Guzzle http requests client
     *
     * @var Client
     */
    protected Client $client;

    /**
     * ChatMicroservice constructor
     *
     * @throws DatabaseException
     */
    public function __construct()
    {

        /**
         * Api parameters initialization
         */
        $this->apiUrl = config('microservices.chat.url');

        $this->headers = [
            'X-Localization' => AuthService::getLocalizationHeader(),
            'Content-Type'   => 'application/json'
        ];

        $this->client = new Client([
            'headers' => $this->headers
        ]);
    }
}
