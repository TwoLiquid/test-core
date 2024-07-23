<?php

namespace App\Microservices\Auth;

use App\Exceptions\DatabaseException;
use App\Microservices\Auth\Traits\AuthTrait;
use App\Microservices\ExceptionTrait;
use App\Services\Auth\AuthService;
use GuzzleHttp\Client;

/**
 * Class AuthMicroservice
 *
 * @package App\Microservices\Auth
 */
class AuthMicroservice
{
    use ExceptionTrait, AuthTrait;

    /**
     * Appearance constant
     */
    const APPEARANCE = 'auth';

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
     * AuthMicroservice constructor
     *
     * @throws DatabaseException
     */
    public function __construct()
    {
        /**
         * Api parameters initialization
         */
        $this->apiUrl = config('microservices.auth.url');

        $this->headers = [
            'X-Authorization' => config('microservices.auth.key'),
            'X-Localization'  => AuthService::getLocalizationHeader(),
            'Content-Type'    => 'application/json'
        ];

        $this->client = new Client([
            'headers' => $this->headers
        ]);
    }
}
