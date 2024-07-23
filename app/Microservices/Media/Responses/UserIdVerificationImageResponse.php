<?php

namespace App\Microservices\Media\Responses;

/**
 * Class UserIdVerificationImageResponse
 *
 * @property int $id
 * @property int $authId
 * @property int $requestId
 * @property string $url
 * @property string $urlMin
 * @property string $mime
 * @property bool $declined
 *
 * @package App\Microservices\Media\Responses
 */
class UserIdVerificationImageResponse extends BaseResponse
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
    public string $requestId;

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
     * UserIdVerificationImageResponse constructor
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

        parent::__construct($message);
    }
}