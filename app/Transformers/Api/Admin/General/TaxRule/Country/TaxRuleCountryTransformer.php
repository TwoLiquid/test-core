<?php

namespace App\Transformers\Api\Admin\General\TaxRule\Country;

use App\Exceptions\DatabaseException;
use App\Models\MySql\TaxRule\TaxRuleCountry;
use App\Repositories\TaxRule\TaxRuleCountryHistoryRepository;
use App\Transformers\Api\Admin\General\TaxRule\Region\TaxRuleRegionListTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class TaxRuleCountryTransformer
 *
 * @package App\Transformers\Api\Admin\General\TaxRule\Country
 */
class TaxRuleCountryTransformer extends BaseTransformer
{
    /**
     * @var TaxRuleCountryHistoryRepository
     */
    protected TaxRuleCountryHistoryRepository $taxRuleCountryHistoryRepository;

    /**
     * TaxRuleCountryTransformer constructor
     */
    public function __construct()
    {
        /** @var TaxRuleCountryHistoryRepository taxRuleCountryHistoryRepository */
        $this->taxRuleCountryHistoryRepository = new TaxRuleCountryHistoryRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'country_place',
        'tax_rule_country_history',
        'tax_rule_regions'
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
                $taxRuleCountry->from_date->format('Y-m-d\TH:i:s.v\Z') :
                null
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
     * @param TaxRuleCountry $taxRuleCountry
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeTaxRuleCountryHistory(TaxRuleCountry $taxRuleCountry) : ?Item
    {
        $taxRuleCountryHistory = $this->taxRuleCountryHistoryRepository->findLastForTaxRuleCountry(
            $taxRuleCountry
        );

        return $taxRuleCountryHistory ? $this->item($taxRuleCountryHistory, new TaxRuleCountryHistoryTransformer) : null;
    }

    /**
     * @param TaxRuleCountry $taxRuleCountry
     *
     * @return Collection|null
     */
    public function includeTaxRuleRegions(TaxRuleCountry $taxRuleCountry) : ?Collection
    {
        $taxRuleRegions = $taxRuleCountry->taxRuleRegions;

        return $taxRuleRegions ? $this->collection($taxRuleRegions, new TaxRuleRegionListTransformer) : null;
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
