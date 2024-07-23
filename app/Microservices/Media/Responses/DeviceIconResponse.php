<?php

namespace App\Microservices\Media\Responses;

/**
 * Class DeviceIconResponse
 *
 * @property int $id
 * @property int $deviceId
 * @property string $url
 * @property string $mime
 *
 * @package App\Microservices\Media\Responses
 */
class DeviceIconResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $deviceId;

    /**
     * @var string
     */
    public string $url;

    /**
     * @var string
     */
    public string $mime;

    /**
     * DeviceIconResponse constructor
     *
     * @param object $response
     * @param string|null $message
     */
    public function __construct(
        object $response,
        ?string $message
    )
    {
        $this->id = $response->id;
        $this->deviceId = $response->device_id;
        $this->url = $response->url;
        $this->mime = $response->mime;

        parent::__construct($message);
    }
}