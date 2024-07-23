<?php

namespace App\Microservices\Media\Responses;

/**
 * Class AlertImageResponse
 *
 * @property int $id
 * @property int $alertId
 * @property string $url
 * @property string $urlMin
 * @property string $mime
 * @property bool $active
 *
 * @package App\Microservices\Media\Responses
 */
class AlertImageResponse extends BaseResponse
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
     * @var string
     */
    public string $urlMin;

    /**
     * @var string
     */
    public string $mime;

    /**
     * @var bool|null
     */
    public ?bool $active;

    /**
     * AlertImageResponse constructor
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
        $this->urlMin = $response->url_min;
        $this->mime = $response->mime;
        $this->active = $response->active;

        parent::__construct($message);
    }
}