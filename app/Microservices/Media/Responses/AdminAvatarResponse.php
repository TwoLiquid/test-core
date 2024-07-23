<?php

namespace App\Microservices\Media\Responses;

/**
 * Class AdminAvatarResponse
 *
 * @property int $id
 * @property int $authId
 * @property string $url
 * @property string $urlMin
 * @property string $mime
 *
 * @package App\Microservices\Media\Responses
 */
class AdminAvatarResponse extends BaseResponse
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
     * AdminAvatarResponse constructor
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
        $this->url = $response->url;
        $this->urlMin = $response->url_min;
        $this->mime = $response->mime;

        parent::__construct($message);
    }
}