<?php

namespace App\Transformers\Api\Admin\Invoice\Tip\Buyer\Pdf;

use App\Models\MySql\Billing;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class BillingTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\Tip\Buyer\Pdf
 */
class BillingTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'country_place',
        'region_place'
    ];

    /**
     * @param Billing $billing
     *
     * @return array
     */
    public function transform(Billing $billing) : array
    {
        return [
            'id'          => $billing->id,
            'first_name'  => $billing->first_name,
            'last_name'   => $billing->last_name,
            'address'     => $billing->address,
            'postal_code' => $billing->postal_code
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
