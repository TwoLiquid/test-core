<?php

namespace App\Microservices\Media\Responses;

/**
 * Class UserImageResponse
 *
 * @property int $id
 * @property int $authId
 * @property string $requestId
 * @property string $url
 * @property string $urlMin
 * @property string $mime
 * @property bool $declined
 * @property int $likes
 *
 * @package App\Microservices\Media\Responses
 */
class UserImageResponse extends BaseResponse
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
    public ?bool $declined;

    /**
     * @var int
     */
    public int $likes;

    /**
     * UserImageResponse constructor
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
        $this->urlMin = $response->url_min;
        $this->mime = $response->mime;
        $this->declined = $response->declined;
        $this->likes = $response->likes;

        parent::__construct($message);
    }
}