<?php

namespace App\Transformers\Api\Guest\Profile\Home;

use App\Models\MySql\Timezone\Timezone;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TimezoneTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home
 */
class TimezoneTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'offset'
    ];

    /**
     * @param Timezone $timezone
     *
     * @return array
     */
    public function transform(Timezone $timezone) : array
    {
        return [
            'id'          => $timezone->id,
            'external_id' => $timezone->external_id,
            'has_dst'     => $timezone->has_dst,
            'in_dst'      => $timezone->in_dst
        ];
    }

    /**
     * @param Timezone $timezone
     *
     * @return Item|null
     */
    public function includeOffset(Timezone $timezone) : ?Item
    {
        $timezoneOffset = null;

        if ($timezone->relationLoaded('offsets')) {
            $timezoneOffset = $timezone->getCurrentOffset();
        }

        return $timezoneOffset ? $this->item($timezoneOffset, new TimezoneOffsetTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'timezone';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'timezones';
    }
}
