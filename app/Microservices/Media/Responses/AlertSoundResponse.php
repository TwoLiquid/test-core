<?php

namespace App\Microservices\Media\Responses;

/**
 * Class AlertSoundResponse
 *
 * @property int $id
 * @property int $alertId
 * @property string $url
 * @property int $duration
 * @property string $mime
 * @property bool $active
 *
 * @package App\Microservices\Media\Responses
 */
class AlertSoundResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int|null
     */
    public ?int $alertId;

    /**
     * @var string
     */
    public string $url;

    /**
     * @var int
     */
    public int $duration;

    /**
     * @var string
     */
    public string $mime;

    /**
     * @var bool|null
     */
    public ?bool $active;

    /**
     * AlertSoundResponse constructor
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
        $this->alertId = $response->alert_id;
        $this->url = $response->url;
        $this->duration = $response->duration;
        $this->mime = $response->mime;
        $this->active = $response->active;

        parent::__construct($message);
    }
}