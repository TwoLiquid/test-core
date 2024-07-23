<?php

namespace App\Microservices\Chat\Transformers;

use App\Microservices\Chat\Responses\DirectPaymentWordResponse;
use App\Transformers\BaseTransformer;

/**
 * Class OffensiveWordTransformer
 *
 * @package App\Microservices\Chat\Transformers
 */
class DirectPaymentWordTransformer extends BaseTransformer
{
    /**
     * @param DirectPaymentWordResponse $directPaymentWordResponse
     * 
     * @return array
     */
    public function transform(DirectPaymentWordResponse $directPaymentWordResponse) : array
    {
        return [
            'id'            => $directPaymentWordResponse->id,
            'language_code' => $directPaymentWordResponse->languageCode,
            'word'          => $directPaymentWordResponse->word
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'direct_payment_word';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'direct_payment_words';
    }
}
