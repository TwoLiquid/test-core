<?php

namespace App\Transformers\Api\Admin\General\TaxRule\Country;

use App\Models\MySql\TaxRule\TaxRuleCountry;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TaxRuleCountryListTransformer
 *
 * @package App\Transformers\Api\Admin\General\TaxRule\Country
 */
class TaxRuleCountryListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'country_place'
    ];

    /**
     * @param TaxRuleCountry $taxRuleCountry
     *
     * @return array
     */
    public function transform(TaxRuleCountry $taxRuleCountry) : array
    {
        return [
            'id'        => $taxRuleCountry->id,
            'tax_rate'  => $taxRuleCountry->tax_rate,
            'from_date' => $taxRuleCountry->from_date ?
                $taxRuleCountry->from_date->format('Y-m-d H:i:s') :
                null,
            'tax_rule_regions_count' => $taxRuleCountry->tax_rule_regions_count
        ];
    }

    /**
     * @param TaxRuleCountry $taxRuleCountry
     *
     * @return Item|null
     */
    public function includeCountryPlace(TaxRuleCountry $taxRuleCountry) : ?Item
    {
        $countryPlace = null;

        if ($taxRuleCountry->relationLoaded('countryPlace')) {
            $countryPlace = $taxRuleCountry->countryPlace;
        }

        return $countryPlace ? $this->item($countryPlace, new CountryPlaceTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tax_rule_country';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tax_rule_countries';
    }
}
