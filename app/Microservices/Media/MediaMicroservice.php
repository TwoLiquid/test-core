<?php

namespace App\Microservices\Media;

use App\Exceptions\DatabaseException;
use App\Microservices\Media\Traits\ActivityMediaTrait;
use App\Microservices\Media\Traits\AdminMediaTrait;
use App\Microservices\Media\Traits\AlertMediaTrait;
use App\Microservices\Media\Traits\AuthMediaTrait;
use App\Microservices\Media\Traits\DeviceMediaTrait;
use App\Microservices\Media\Traits\PaymentMethodMediaTrait;
use App\Microservices\Media\Traits\PlatformMediaTrait;
use App\Microservices\Media\Traits\SearchMediaTrait;
use App\Microservices\Media\Traits\UserMediaTrait;
use App\Microservices\Media\Traits\VatNumberProofMediaTrait;
use App\Microservices\Media\Traits\VybeMediaTrait;
use App\Microservices\Media\Traits\CategoryMediaTrait;
use App\Microservices\Media\Traits\WithdrawalReceiptProofMediaTrait;
use App\Microservices\ExceptionTrait;
use App\Services\Auth\AuthService;
use GuzzleHttp\Client;

/**
 * Class MediaMicroservice
 *
 * @package App\Microservices
 */
class MediaMicroservice
{
    use ExceptionTrait, UserMediaTrait, ActivityMediaTrait, VybeMediaTrait,
        AlertMediaTrait, SearchMediaTrait, VatNumberProofMediaTrait, CategoryMediaTrait, DeviceMediaTrait,
        AdminMediaTrait, PlatformMediaTrait, WithdrawalReceiptProofMediaTrait, PaymentMethodMediaTrait, AuthMediaTrait;

    /**
     * Appearnce constant
     */
    const APPEARANCE = 'media';

    /**
     * Media microservice hosting url
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
     * Bearer token
     *
     * @var string|null
     */
    protected ?string $token = null;

    /**
     * Guzzle http requests client
     *
     * @var Client
     */
    protected Client $client;

    /**
     * MediaMicroservice constructor
     *
     * @param string|null $token
     *
     * @throws DatabaseException
     */
    public function __construct(
        ?string $token = null
    )
    {
        /**
         * Api parameters initialization
         */
        $this->apiUrl = config('microservices.media.url');

        /**
         * Collecting headers
         */
        $this->headers = [
            'X-Authorization' => config('microservices.media.key'),
            'X-Localization'  => AuthService::getLocalizationHeader(),
            'Content-Type'    => 'application/json'
        ];

        /**
         * Initializing token if exists
         */
        $this->initializeToken(
            $token
        );

        $this->client = new Client([
            'headers' => $this->headers
        ]);
    }

    /**
     * @param string|null $token
     */
    private function initializeToken(
        ?string $token
    ) : void
    {
        /**
         * Setting up system or user token if exists
         */
        if ($token) {
            $this->token = $token;
        } elseif (request()->bearerToken()) {
            $this->token = request()->bearerToken();
        }

        /**
         * Checking token has been set
         */
        if ($this->token) {
            $this->headers += [
                'Authorization' => 'Bearer ' . $this->token
            ];
        }
    }
}
