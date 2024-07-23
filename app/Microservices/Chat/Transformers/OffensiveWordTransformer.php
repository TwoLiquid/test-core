<?php

namespace App\Microservices\Chat\Transformers;

use App\Microservices\Chat\Responses\OffensiveWordResponse;
use App\Transformers\BaseTransformer;

/**
 * Class OffensiveWordTransformer
 *
 * @package App\Microservices\Chat\Transformers
 */
class OffensiveWordTransformer extends BaseTransformer
{
    /**
     * @param OffensiveWordResponse $offensiveWordResponse
     *
     * @return array
     */
    public function transform(OffensiveWordResponse $offensiveWordResponse) : array
    {
        return [
            'id'            => $offensiveWordResponse->id,
            'language_code' => $offensiveWordResponse->languageCode,
            'word'          => $offensiveWordResponse->word
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'offensive_word';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'offensive_words';
    }
}
