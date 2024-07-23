<?php

namespace App\Services\Bugsnag;

use App\Exceptions\BaseException;
use App\Services\Bugsnag\Interfaces\BugsnagServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

/**
 * Class BugsnagService
 *
 * @package App\Services\Bugsnag
 */
class BugsnagService implements BugsnagServiceInterface
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * BugsnagService constructor
     */
    public function __construct()
    {
        /** @var Client client */
        $this->client = new Client([
            'headers' => [
                'Authorization' => 'token ' . config('bugsnag.auth_token'),
                'X-Version'     => 2
            ]
        ]);
    }

    /**
     * @return bool
     *
     * @throws BaseException
     * @throws GuzzleException
     */
    public function checkApiIntegration() : bool
    {
        try {

            /**
             * Getting user organization projects
             */
            $response = $this->client->request(
                'GET',
                'https://api.bugsnag.com/organizations/' . config('bugsnag.organization_id') . '/projects'
            );

            /**
             * Getting projects data
             */
            $projects = json_decode(
                $response->getBody()->getContents()
            );
        } catch (Exception $exception) {

            /**
             * Checking exception code
             */
            if ($exception->getCode() == 401) {
                throw new BaseException(
                    'Incorrect auth key.',
                    $exception->getMessage(),
                    $exception->getCode()
                );
            } else {
                throw new BaseException(
                    'Incorrect organization id.',
                    $exception->getMessage(),
                    $exception->getCode()
                );
            }
        }

        /**
         * Iterating projects API response data
         */
        foreach ($projects as $project) {

            /**
             * Checking project id
             */
            if ($project->id == config('bugsnag.project_id')) {

                /**
                 * Checking api key
                 */
                if ($project->api_key == config('bugsnag.api_key')) {
                    return true;
                } else {
                    throw new BaseException(
                        'Incorrect api key.',
                        null,
                        422
                    );
                }
            }
        }

        throw new BaseException(
            'Incorrect project id.',
            null,
            422
        );
    }
}
