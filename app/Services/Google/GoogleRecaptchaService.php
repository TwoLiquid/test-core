<?php

namespace App\Services\Google;

use App\Exceptions\BaseException;
use App\Services\Google\Interfaces\GoogleRecaptchaServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

/**
 * Class GoogleRecaptchaService
 *
 * @package App\Services\Google
 */
class GoogleRecaptchaService implements GoogleRecaptchaServiceInterface
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * GoogleRecaptchaService constructor
     */
    public function __construct()
    {
        /** @var Client client */
        $this->client = new Client();
    }

    /**
     * @return bool
     *
     * @throws BaseException
     * @throws GuzzleException
     */
    public function checkSiteKey() : bool
    {
        try {

            /**
             * Getting a google recaptcha anchor site contents
             */
            $response = $this->client->request(
                'GET',
                config('recaptcha.anchor_url'),
                [
                    'query' => [
                        'k' => config('recaptcha.api_site_key')
                    ]
                ]
            );

            return !str_contains(
                $response->getBody()->getContents(),
                'Invalid site key'
            );
        } catch (Exception $exception) {
            if ($exception->getCode() == 404) {
                throw new BaseException(
                    'Bad google recaptcha anchor url.',
                    $exception->getMessage(),
                    404
                );
            } else {
                throw new BaseException(
                    'Checking google recaptcha site key error.',
                    $exception->getMessage(),
                    $exception->getCode()
                );
            }
        }
    }

    /**
     * @return bool
     *
     * @throws BaseException
     * @throws GuzzleException
     */
    public function checkSecretKey() : bool
    {
        try {

            /**
             * Getting a google recaptcha verify response
             */
            $response = $this->client->request(
                'POST',
                config('recaptcha.verify_url'),
                [
                    'form_params' => [
                        'secret' => config('recaptcha.api_secret_key')
                    ]
                ]
            );

            /**
             * Getting data from response
             */
            $responseData = json_decode(
                $response->getBody()->getContents(),
                true
            );

            /**
             * Checking response error codes existence
             */
            if (isset($responseData['error-codes'])) {

                /**
                 * Checking secret key error code
                 */
                if (!in_array(
                    'invalid-input-secret',
                    $responseData['error-codes'])
                ) {
                    return true;
                }
            }
        } catch (Exception $exception) {
            if ($exception->getCode() == 404) {
                throw new BaseException(
                    'Bad google recaptcha verify url.',
                    $exception->getMessage(),
                    404
                );
            } else {
                throw new BaseException(
                    'Checking google recaptcha secret key error.',
                    $exception->getMessage(),
                    $exception->getCode()
                );
            }
        }

        return false;
    }
}
