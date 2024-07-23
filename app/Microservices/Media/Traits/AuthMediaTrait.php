<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\BaseResponse;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Trait AuthMediaTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait AuthMediaTrait
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
                trans('exceptions/microservice/media/auth.' . __FUNCTION__)
            );
        }
    }
}
