<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\AlertImageCollectionResponse;
use App\Microservices\Media\Responses\AlertSoundCollectionResponse;
use App\Microservices\Media\Responses\BaseResponse;
use App\Models\MySql\Alert\Alert;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Trait AlertMediaTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait AlertMediaTrait
{
    /**
     * @param Alert $alert
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getForAlert(
        Alert $alert
    ) : array
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/alert/' . $alert->id . '/all'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/alert.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Collection $alerts
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getForAlerts(
        Collection $alerts
    ) : array
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/alerts', [
                'form_params' => [
                    'alerts_ids' => $alerts->pluck('id')
                        ->values()
                        ->toArray()
                ]
            ]);

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/alert.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Alert $alert
     * @param array $imagesArray
     *
     * @return AlertImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeAlertImages(
        Alert $alert,
        array $imagesArray
    ) : AlertImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/alert/' . $alert->id . '/images', [
                'form_params' => [
                    'alert_images' => $imagesArray
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new AlertImageCollectionResponse(
                $responseData->alert_images,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/alert.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Alert $alert
     * @param array $soundsArray
     * @return AlertSoundCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeAlertSounds(
        Alert $alert,
        array $soundsArray
    ) : AlertSoundCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/alert/' . $alert->id . '/sounds', [
                'form_params' => [
                    'alert_sounds' => $soundsArray
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new AlertSoundCollectionResponse(
                $responseData->alert_sounds,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/alert.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $alertImagesIds
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deleteAlertImages(
        array $alertImagesIds
    ) : BaseResponse
    {
        try {
            $response = $this->client->request(
                'DELETE',
                $this->apiUrl . '/alert/images', [
                'form_params' => [
                    'alert_images_ids' => $alertImagesIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new BaseResponse(
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/alert.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $alertSoundsIds
     * 
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deleteAlertSounds(
        array $alertSoundsIds
    ) : BaseResponse
    {
        try {
            $response = $this->client->request(
                'DELETE',
                $this->apiUrl . '/alert/sounds', [
                'form_params' => [
                    'alert_sounds_ids' => $alertSoundsIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new BaseResponse(
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/alert.' . __FUNCTION__)
            );
        }
    }
}