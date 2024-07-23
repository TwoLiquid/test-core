<?php

namespace App\Transformers\Api\Admin\General\TaxRule\Region;

use App\Exceptions\DatabaseException;
use App\Models\MySql\TaxRule\TaxRuleRegion;
use App\Repositories\TaxRule\TaxRuleRegionHistoryRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class TaxRuleRegionTransformer
 *
 * @package App\Transformers\Api\Admin\General\TaxRule\Region
 */
class TaxRuleRegionTransformer extends BaseTransformer
{
    /**
     * @var TaxRuleRegionHistoryRepository
     */
    protected TaxRuleRegionHistoryRepository $taxRuleRegionHistoryRepository;

    /**
     * TaxRuleRegionTransformer constructor
     */
    public function __construct()
    {
        /** @var TaxRuleRegionHistoryRepository taxRuleRegionHistoryRepository */
        $this->taxRuleRegionHistoryRepository = new TaxRuleRegionHistoryRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'region_place',
        'tax_rule_region_history'
    ];

    /**
     * @param TaxRuleRegion $taxRuleRegion
     *
     * @return array
     */
    public function transform(TaxRuleRegion $taxRuleRegion) : array
    {
        return [
            'id'        => $taxRuleRegion->id,
            'tax_rate'  => $taxRuleRegion->tax_rate,
            'from_date' => $taxRuleRegion->from_date ?
                $taxRuleRegion->from_date->format('Y-m-d H:i:s') :
                null
        ];
    }

    /**
     * @param TaxRuleRegion $taxRuleRegion
     *
     * @return Item|null
     */
    public function includeRegionPlace(TaxRuleRegion $taxRuleRegion) : ?Item
    {
        $regionPlace = null;

        if ($taxRuleRegion->relationLoaded('regionPlace')) {
            $regionPlace = $taxRuleRegion->regionPlace;
        }

        return $regionPlace ? $this->item($regionPlace, new RegionPlaceTransformer) : null;
    }

    /**
     * @param TaxRuleRegion $taxRuleRegion
     *
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeTaxRuleRegionHistory(TaxRuleRegion $taxRuleRegion) : ?Item
    {
        $taxRuleRegionHistory = $this->taxRuleRegionHistoryRepository->findLastForTaxRuleRegion(
            $taxRuleRegion
        );

        return $taxRuleRegionHistory ? $this->item($taxRuleRegionHistory, new TaxRuleRegionHistoryTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tax_rule_region';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tax_rule_regions';
    }
}
