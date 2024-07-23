<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\PlatformIconCollectionResponse;
use App\Microservices\Media\Responses\PlatformIconResponse;
use App\Models\MySql\Platform;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

/**
 * Trait PlatformMediaTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait PlatformMediaTrait
{
    /**
     * @param Platform $platform
     *
     * @return PlatformIconCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getPlatformIcons(
        Platform $platform
    ) : PlatformIconCollectionResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/platform/' . $platform->id . '/icons'
            );

            $responseData = json_decode(
                $response->getBody()->getContents(),
            );

            return new PlatformIconCollectionResponse(
                $responseData->platform_icons,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/platform.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $platformsIds
     *
     * @return PlatformIconCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getPlatformsIcons(
        array $platformsIds
    ) : PlatformIconCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/platforms/icons', [
                'form_params' => [
                    'platforms_ids' => $platformsIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents(),
            );

            return new PlatformIconCollectionResponse(
                $responseData->platform_icons,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/platform.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Platform $platform
     * @param string $content
     * @param string $extension
     * @param string $mime
     *
     * @return PlatformIconResponse|null
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storePlatformIcon(
        Platform $platform,
        string $content,
        string $extension,
        string $mime
    ) : ?PlatformIconResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/platform/' . $platform->id . '/icon', [
                'form_params' => [
                    'content'   => $content,
                    'extension' => $extension,
                    'mime'      => $mime
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            if ($responseData->platform_icon) {
                return new PlatformIconResponse(
                    $responseData->platform_icon,
                    $responseData->message
                );
            }

            return null;
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/platform.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Platform $platform
     *
     * @return PlatformIconCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deletePlatformIcons(
        Platform $platform
    ) : PlatformIconCollectionResponse
    {
        try {
            $response = $this->client->request(
                'DELETE',
                $this->apiUrl . '/platform/' . $platform->id . '/icons'
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new PlatformIconCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/platform.' . __FUNCTION__)
            );
        }
    }
}
