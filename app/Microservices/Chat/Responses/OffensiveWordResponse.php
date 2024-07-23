<?php

namespace App\Microservices\Chat\Responses;

/**
 * Class OffensiveWordResponse
 *
 * @property int $id
 * @property string $languageCode
 * @property string $word
 *
 * @package App\Microservices\Chat\Responses
 */
class OffensiveWordResponse extends BaseResponse
{
    /**
     * @var int
     */
    public int $id;

    /**
     * @var string
     */
    public string $languageCode;

    /**
     * @var string
     */
    public string $word;

    /**
     * OffensiveWordResponse constructor
     *
     * @param object $response
     * @param string $message
     */
    public function __construct(
        object $response,
        string $message
    )
    {
        $this->id = $response->id;
        $this->languageCode = $response->language_code;
        $this->word = $response->word;

        parent::__construct($message);
    }
}