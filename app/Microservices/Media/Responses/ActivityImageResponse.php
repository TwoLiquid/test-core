<?php

namespace App\Microservices\Media\Responses;

/**
 * Class ActivityImageResponse
 *
 * @property int $id
 * @property int $activityId
 * @property string $type
 * @property string $url
 * @property string $urlMin
 * @property string $mime
 *
 * @package App\Microservices\Media\Responses
 */
class ActivityImageResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $activityId;

    /**
     * @var string
     */
    public string $type;

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
     * ActivityImageResponse constructor
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
        $this->activityId = $response->activity_id;
        $this->type = $response->type;
        $this->url = $response->url;
        $this->urlMin = $response->url_min;
        $this->mime = $response->mime;

        parent::__construct($message);
    }
}