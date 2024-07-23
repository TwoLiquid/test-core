<?php

namespace App\Transformers\Api\General\Setting\Billing;

use App\Models\MySql\Billing;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class BillingTransformer
 *
 * @package App\Transformers\Api\General\Setting\Billing
 */
class BillingTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'country_place',
        'region_place',
        'phone_code_country_place'
    ];

    /**
     * @param Billing $billing
     *
     * @return array
     */
    public function transform(Billing $billing) : array
    {
        return [
            'first_name'    => $billing->first_name,
            'last_name'     => $billing->last_name,
            'city'          => $billing->city,
            'postal_code'   => $billing->postal_code,
            'address'       => $billing->address,
            'phone'         => $billing->phone,
            'business_info' => $billing->business_info,
            'company_name'  => $billing->company_name,
            'vat_number'    => $billing->vat_number
        ];
    }

    /**
     * @param Billing $billing
     *
     * @return Item|null
     */
    public function includeCountryPlace(Billing $billing) : ?Item
    {
        $countryPlace = null;

        if ($billing->relationLoaded('countryPlace')) {
            $countryPlace = $billing->countryPlace;
        }

        return $countryPlace ? $this->item($countryPlace, new CountryPlaceTransformer) : null;
    }

    /**
     * @param Billing $billing
     *
     * @return Item|null
     */
    public function includeRegionPlace(Billing $billing) : ?Item
    {
        $regionPlace = null;

        if ($billing->relationLoaded('regionPlace')) {
            $regionPlace = $billing->regionPlace;
        }

        return $regionPlace ? $this->item($regionPlace, new RegionPlaceTransformer) : null;
    }

    /**
     * @param Billing $billing
     *
     * @return Item|null
     */
    public function includePhoneCodeCountryPlace(Billing $billing) : ?Item
    {
        $phoneCodeCountryPlace = null;

        if ($billing->relationLoaded('phoneCodeCountryPlace')) {
            $phoneCodeCountryPlace = $billing->phoneCodeCountryPlace;
        }

        return $phoneCodeCountryPlace ? $this->item($phoneCodeCountryPlace, new CountryPlaceTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'billing';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'billings';
    }
}
