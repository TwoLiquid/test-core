<?php

namespace App\Transformers\Api\General\Alert;

use App\Models\MySql\Alert\AlertProfanityFilter;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class AlertProfanityFilterTransformer
 *
 * @package App\Transformers\Api\General\Alert
 */
class AlertProfanityFilterTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'words'
    ];

    /**
     * @param AlertProfanityFilter $alertProfanityFilter
     *
     * @return array
     */
    public function transform(AlertProfanityFilter $alertProfanityFilter) : array
    {
        return [
            'id'            => $alertProfanityFilter->id,
            'replace'       => $alertProfanityFilter->replace,
            'replace_with'  => $alertProfanityFilter->replace_with,
            'hide_messages' => $alertProfanityFilter->hide_messages,
            'bad_words'     => $alertProfanityFilter->bad_words
        ];
    }

    /**
     * @param AlertProfanityFilter $alertProfanityFilter
     *
     * @return Collection|null
     */
    public function includeWords(AlertProfanityFilter $alertProfanityFilter) : ?Collection
    {
        $words = null;

        if ($alertProfanityFilter->relationLoaded('words')) {
            $words = $alertProfanityFilter->words;
        }

        return $words ? $this->collection($words, new AlertProfanityWordTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert_profanity_filter';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alert_profanity_filters';
    }
}
