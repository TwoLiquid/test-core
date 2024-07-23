<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

/**
 * Trait UserMediaMicroserviceTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait SearchMediaTrait
{
    /**
     * @param array|null $authIds
     * @param array|null $vybesImagesIds
     * @param array|null $vybesVideosIds
     * @param array|null $activitiesIds
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getClientSearchMedia(
        ?array $authIds,
        ?array $vybesImagesIds,
        ?array $vybesVideosIds,
        ?array $activitiesIds
    ) : array
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/search/client/global', [
                'form_params' => [
                    'auth_ids'        => $authIds,
                    'vybe_images_ids' => $vybesImagesIds,
                    'vybe_videos_ids' => $vybesVideosIds,
                    'activities_ids'  => $activitiesIds
                ]
            ]);

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/search.' . __FUNCTION__)
            );
        }
    }
}