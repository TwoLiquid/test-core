<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterEmailVerifyRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class VerificationController
 *
 * @package App\Http\Controllers\Auth
 */
class VerificationController extends Controller
{
    /**
     * Guzzle http request client
     *
     * @var Client
     */
    protected Client $client;

    /**
     * VerificationController constructor
     */
    public function __construct()
    {
        /**
         * Api parameters initialization
         */
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    /**
     * @param RegisterEmailVerifyRequest $request
     *
     * @return array
     *
     * @throws GuzzleException
     */
    public function registerEmailVerify(
        RegisterEmailVerifyRequest $request
    ) : array
    {
        try {
            $response = $this->client->request(
                'GET',
                route('api.auth.register.email.verify'), [
                    'query' => [
                        'token' => $request->input('token'),
                        'email' => $request->input('email')
                    ]
                ]
            );

            return json_decode(
                $response->getBody()->getContents(),
            );
        } catch (ClientException $exception) {
            dd($exception->getMessage());
        }
    }
}
