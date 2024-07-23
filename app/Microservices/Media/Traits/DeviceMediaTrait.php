<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\DeviceIconCollectionResponse;
use App\Microservices\Media\Responses\DeviceIconResponse;
use App\Models\MySql\Device;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Trait DeviceMediaTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait DeviceMediaTrait
{
    /**
     * @param Device $device
     *
     * @return DeviceIconCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getDeviceIcons(
        Device $device
    ) : DeviceIconCollectionResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/device/' . $device->id . '/icons'
            );

            $responseData = json_decode(
                $response->getBody()->getContents(),
            );

            return new DeviceIconCollectionResponse(
                $responseData->device_icons,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/device.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $devicesIds
     *
     * @return DeviceIconCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getDevicesIcons(
        array $devicesIds
    ) : DeviceIconCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/devices/icons', [
                'form_params' => [
                    'devices_ids' => $devicesIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents(),
            );

            return new DeviceIconCollectionResponse(
                $responseData->device_icons,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/device.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Device $device
     * @param string $content
     * @param string $extension
     * @param string $mime
     *
     * @return DeviceIconResponse|null
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeDeviceIcon(
        Device $device,
        string $content,
        string $extension,
        string $mime
    ) : ?DeviceIconResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/device/' . $device->id . '/icon', [
                'form_params' => [
                    'content'   => $content,
                    'extension' => $extension,
                    'mime'      => $mime
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            if ($responseData->device_icon) {
                return new DeviceIconResponse(
                    $responseData->device_icon,
                    $responseData->message
                );
            }

            return null;
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/device.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Device $device
     *
     * @return DeviceIconCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deleteDeviceIcons(
        Device $device
    ) : DeviceIconCollectionResponse
    {
        try {
            $response = $this->client->request(
                'DELETE',
                $this->apiUrl . '/device/' . $device->id . '/icons'
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new DeviceIconCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/device.' . __FUNCTION__)
            );
        }
    }
}
