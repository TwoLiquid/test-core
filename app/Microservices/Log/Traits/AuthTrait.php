<?php

namespace App\Microservices\Log\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Log\Responses\BaseResponse;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Trait AuthTrait
 *
 * @package App\Microservices\Log\Traits
 */
trait AuthTrait
{
    /**
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function test() : BaseResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/auth/test'
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new BaseResponse(
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/auth.' . __FUNCTION__)
            );
        }
    }
}
