<?php

namespace App\Microservices\Media\Responses;

/**
 * Class VybeVideoThumbnailResponse
 *
 * @property int $id
 * @property int $videoId
 * @property string $url
 * @property string $mime
 *
 * @package App\Microservices\Media\Responses
 */
class VybeVideoThumbnailResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $videoId;

    /**
     * @var string
     */
    public string $url;

    /**
     * @var string
     */
    public string $mime;

    /**
     * VybeVideoThumbnailResponse constructor
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
        $this->videoId = $response->video_id;
        $this->url = $response->url;
        $this->mime = $response->mime;

        parent::__construct($message);
    }
}