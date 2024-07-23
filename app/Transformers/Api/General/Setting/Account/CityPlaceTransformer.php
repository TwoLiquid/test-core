<?php

namespace App\Transformers\Api\General\Setting\Account;

use App\Models\MySql\Place\CityPlace;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class CityPlaceTransformer
 *
 * @package App\Transformers\Api\General\Setting\Account
 */
class CityPlaceTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'timezone'
    ];

    /**
     * @param CityPlace $cityPlace
     *
     * @return array
     */
    public function transform(CityPlace $cityPlace) : array
    {
        return [
            'id'       => $cityPlace->id,
            'place_id' => $cityPlace->place_id,
            'name'     => $cityPlace->name
        ];
    }

    /**
     * @param CityPlace $cityPlace
     *
     * @return Item|null
     */
    public function includeTimezone(CityPlace $cityPlace) : ?Item
    {
        $timezone = null;

        if ($cityPlace->relationLoaded('timezone')) {
            $timezone = $cityPlace->timezone;
        }

        return $timezone ? $this->item($timezone, new TimezoneTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'city_place';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'city_places';
    }
}
