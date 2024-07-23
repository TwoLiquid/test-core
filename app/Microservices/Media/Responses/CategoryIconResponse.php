<?php

namespace App\Microservices\Media\Responses;

/**
 * Class CategoryIconResponse
 *
 * @property int $id
 * @property int $categoryId
 * @property string $url
 * @property string $mime
 *
 * @package App\Microservices\Media\Responses
 */
class CategoryIconResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var int
     */
    public int $categoryId;

    /**
     * @var string
     */
    public string $url;

    /**
     * @var string
     */
    public string $mime;

    /**
     * CategoryIconResponse constructor
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
        $this->categoryId = $response->category_id;
        $this->url = $response->url;
        $this->mime = $response->mime;

        parent::__construct($message);
    }
}