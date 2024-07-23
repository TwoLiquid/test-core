<?php

namespace App\Transformers\Api\General\Alert;

use App\Models\MySql\Alert\AlertProfanityWord;
use App\Transformers\BaseTransformer;

/**
 * Class AlertProfanityWordTransformer
 *
 * @package App\Transformers\Api\General\Alert
 */
class AlertProfanityWordTransformer extends BaseTransformer
{
    /**
     * @param AlertProfanityWord $alertProfanityWord
     *
     * @return array
     */
    public function transform(AlertProfanityWord $alertProfanityWord) : array
    {
        return [
            'id'   => $alertProfanityWord->id,
            'word' => $alertProfanityWord->word
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert_profanity_word';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alert_profanity_words';
    }
}
