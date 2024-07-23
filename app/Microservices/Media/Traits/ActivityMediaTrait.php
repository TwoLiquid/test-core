<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\ActivityImageCollectionResponse;
use App\Models\MySql\Activity\Activity;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Trait ActivityMediaTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait ActivityMediaTrait
{
    /**
     * @param Activity $activity
     *
     * @return ActivityImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getForActivity(
        Activity $activity
    ) : ActivityImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/activity/' . $activity->id
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            if (isset($responseData->activity_images)) {
                return new ActivityImageCollectionResponse(
                    $responseData->activity_images,
                    $responseData->message
                );
            }

            return new ActivityImageCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/activity.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $activitiesIds
     *
     * @return ActivityImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getForActivities(
        array $activitiesIds
    ) : ActivityImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/activities', [
                'form_params' => [
                    'activities_ids' => $activitiesIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            if (isset($responseData->activity_images)) {
                return new ActivityImageCollectionResponse(
                    $responseData->activity_images,
                    $responseData->message
                );
            }

            return new ActivityImageCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/activity.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Activity $activity
     * @param array $imagesArray
     *
     * @return ActivityImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeActivityImages(
        Activity $activity,
        array $imagesArray
    ) : ActivityImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/activity/' . $activity->id . '/images', [
                'form_params' => [
                    'activity_images' => $imagesArray
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new ActivityImageCollectionResponse(
                $responseData->activity_images,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/activity.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Activity $activity
     *
     * @return ActivityImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deleteForActivity(
        Activity $activity
    ) : ActivityImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'DELETE',
                $this->apiUrl . '/activity/' . $activity->id . '/images'
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            if (isset($responseData->activity_images)) {
                return new ActivityImageCollectionResponse(
                    $responseData->activity_images,
                    $responseData->message
                );
            }

            return new ActivityImageCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/activity.' . __FUNCTION__)
            );
        }
    }
}
