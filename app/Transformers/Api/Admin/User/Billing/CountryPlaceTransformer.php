<?php

namespace App\Transformers\Api\Admin\User\Billing;

use App\Models\MySql\Place\CountryPlace;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class CountryPlaceTransformer
 *
 * @package App\Transformers\Api\Admin\User\Billing
 */
class CountryPlaceTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'phone_code'
    ];

    /**
     * @param CountryPlace $countryPlace
     *
     * @return array
     */
    public function transform(CountryPlace $countryPlace) : array
    {
        return [
            'id'          => $countryPlace->id,
            'place_id'    => $countryPlace->place_id,
            'name'        => $countryPlace->name,
            'code'        => $countryPlace->code,
            'has_regions' => $countryPlace->has_regions
        ];
    }

    /**
     * @param CountryPlace $countryPlace
     *
     * @return Item|null
     */
    public function includePhoneCode(CountryPlace $countryPlace) : ?Item
    {
        $phoneCode = null;

        if ($countryPlace->relationLoaded('defaultPhoneCode')) {
            $phoneCode = $countryPlace->defaultPhoneCode;
        }

        return $phoneCode ? $this->item($phoneCode, new PhoneCodeTransformer) : null;
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
