<?php

namespace App\Microservices\Media\Responses;

/**
 * Class PlatformIconResponse
 *
 * @property int $id
 * @property int $platformId
 * @property string $url
 * @property string $mime
 *
 * @package App\Microservices\Media\Responses
 */
class PlatformIconResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $platformId;

    /**
     * @var string
     */
    public string $url;

    /**
     * @var string
     */
    public string $mime;

    /**
     * PlatformIconResponse constructor
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
        $this->platformId = $response->platform_id;
        $this->url = $response->url;
        $this->mime = $response->mime;

        parent::__construct($message);
    }
}