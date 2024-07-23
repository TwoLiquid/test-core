<?php

namespace App\Microservices\Media\Responses;

/**
 * Class VybeVideoResponse
 *
 * @property int $id
 * @property string $url
 * @property int $duration
 * @property string $mime
 * @property bool $main
 * @property bool $declined
 * @property VybeVideoThumbnailResponse $thumbnail
 *
 * @package App\Microservices\Media\Responses
 */
class VybeVideoResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

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
     * @var bool
     */
    public bool $main;

    /**
     * @var bool
     */
    public bool $declined;

    /**
     * @var VybeVideoThumbnailResponse
     */
    public VybeVideoThumbnailResponse $thumbnail;

    /**
     * VybeVideoResponse constructor
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
        $this->url = $response->url;
        $this->duration = $response->duration;
        $this->mime = $response->mime;
        $this->main = $response->main;
        $this->declined = $response->declined;
        $this->thumbnail = new VybeVideoThumbnailResponse(
            $response->thumbnail,
            null
        );

        parent::__construct($message);
    }
}