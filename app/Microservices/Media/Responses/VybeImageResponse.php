<?php

namespace App\Microservices\Media\Responses;

/**
 * Class VybeImageResponse
 *
 * @property int $id
 * @property string $url
 * @property string $urlMin
 * @property string $mime
 * @property bool $main
 * @property bool $declined
 *
 * @package App\Microservices\Media\Responses
 */
class VybeImageResponse extends BaseResponse
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
     * @var string
     */
    public string $urlMin;

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
     * VybeImageResponse constructor
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
        $this->urlMin = $response->url_min;
        $this->mime = $response->mime;
        $this->main = $response->main;
        $this->declined = $response->declined;

        parent::__construct($message);
    }
}