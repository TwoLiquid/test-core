<?php

namespace App\Transformers\Api\Admin\General\TaxRule\Region;

use App\Models\MongoDb\TaxRule\TaxRuleRegionHistory;
use App\Transformers\BaseTransformer;

/**
 * Class TaxRuleRegionHistoryTransformer
 *
 * @package App\Transformers\Api\Admin\General\TaxRule\Region
 */
class TaxRuleRegionHistoryTransformer extends BaseTransformer
{
    /**
     * @param TaxRuleRegionHistory $taxRuleRegionHistory
     *
     * @return array
     */
    public function transform(TaxRuleRegionHistory $taxRuleRegionHistory) : array
    {
        return [
            'id'            => $taxRuleRegionHistory->_id,
            'from_tax_rate' => $taxRuleRegionHistory->from_tax_rate,
            'to_tax_rate'   => $taxRuleRegionHistory->to_tax_rate,
            'from_date'     => $taxRuleRegionHistory->from_date ?
                $taxRuleRegionHistory->from_date->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'to_date'       => $taxRuleRegionHistory->to_date ?
                $taxRuleRegionHistory->to_date->format('Y-m-d H:i:s') :
                null
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tax_rule_region_history';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tax_rule_region_history';
    }
}
