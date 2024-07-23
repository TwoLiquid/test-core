<?php

namespace App\Transformers\Api\Admin\User\Information\Follower;

use App\Models\MySql\Place\CountryPlace;
use App\Transformers\BaseTransformer;

/**
 * Class CountryPlaceTransformer
 *
 * @package App\Transformers\Api\Admin\User\Information\Follower
 */
class CountryPlaceTransformer extends BaseTransformer
{
    /**
     * @param CountryPlace $countryPlace
     *
     * @return array
     */
    public function transform(CountryPlace $countryPlace) : array
    {
        return [
            'id'       => $countryPlace->id,
            'place_id' => $countryPlace->place_id,
            'name'     => $countryPlace->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'country_place';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'country_places';
    }
}
