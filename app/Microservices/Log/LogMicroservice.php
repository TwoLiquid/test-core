<?php

namespace App\Microservices\Log;

use App\Exceptions\DatabaseException;
use App\Microservices\ExceptionTrait;
use App\Microservices\Log\Traits\AuthTrait;
use App\Microservices\Log\Traits\UserWalletTransactionLogTrait;
use App\Services\Auth\AuthService;
use GuzzleHttp\Client;

/**
 * Class LogMicroservice
 *
 * @package App\Microservices\Log
 */
class LogMicroservice
{
    use ExceptionTrait, AuthTrait, UserWalletTransactionLogTrait;

    /**
     * Appearance constant
     */
    const APPEARANCE = 'log';

    /**
     * Log microservice hosting url
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
     * LogMicroservice constructor
     *
     * @throws DatabaseException
     */
    public function __construct()
    {
        /**
         * Api parameters initialization
         */
        $this->apiUrl = config('microservices.log.url');

        $this->headers = [
            'X-Authorization' => config('microservices.log.key'),
            'X-Localization'  => AuthService::getLocalizationHeader(),
            'Content-Type'    => 'application/json'
        ];

        $this->client = new Client([
            'headers' => $this->headers
        ]);
    }
}
