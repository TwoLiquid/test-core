<?php

namespace App\Transformers\Api\Guest\PhoneCode;

use App\Models\MySql\PhoneCode;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class PhoneCodeTransformer
 *
 * @package App\Transformers\Api\Guest\PhoneCode
 */
class PhoneCodeTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'country_place'
    ];

    /**
     * @param PhoneCode $phoneCode
     *
     * @return array
     */
    public function transform(PhoneCode $phoneCode) : array
    {
        return [
            'id'   => $phoneCode->id,
            'code' => $phoneCode->code
        ];
    }

    /**
     * @param PhoneCode $phoneCode
     *
     * @return Item|null
     */
    public function includeCountryPlace(PhoneCode $phoneCode) : ?Item
    {
        $countryPlace = null;

        if ($phoneCode->countryPlace()) {
            $countryPlace = $phoneCode->countryPlace;
        }

        return $countryPlace ? $this->item($countryPlace, new CountryPlaceTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'phone_code';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'phone_codes';
    }
}
