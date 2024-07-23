<?php

namespace App\Microservices\Media\Responses;

/**
 * Class UserVideoResponse
 *
 * @property int $id
 * @property int $authId
 * @property string $requestId
 * @property string $url
 * @property int $duration
 * @property string $mime
 * @property bool $declined
 * @property int $likes
 * @property UserVideoThumbnailResponse $thumbnail
 *
 * @package App\Microservices\Media\Responses
 */
class UserVideoResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $authId;

    /**
     * @var string|null
     */
    public ?string $requestId;

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
    public ?bool $declined;

    /**
     * @var int
     */
    public int $likes;

    /**
     * @var UserVideoThumbnailResponse
     */
    public UserVideoThumbnailResponse $thumbnail;

    /**
     * UserVideoResponse constructor
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
        $this->authId = $response->auth_id;
        $this->requestId = $response->request_id;
        $this->url = $response->url;
        $this->duration = $response->duration;
        $this->mime = $response->mime;
        $this->declined = $response->declined;
        $this->likes = $response->likes;
        $this->thumbnail = new UserVideoThumbnailResponse(
            $response->thumbnail,
            null
        );

        parent::__construct($message);
    }
}