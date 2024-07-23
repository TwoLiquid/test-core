<?php

namespace App\Transformers\Api\Admin\General\TaxRule\Country;

use App\Models\MongoDb\TaxRule\TaxRuleCountryHistory;
use App\Transformers\BaseTransformer;

/**
 * Class TaxRuleCountryHistoryTransformer
 *
 * @package App\Transformers\Api\Admin\General\TaxRule\Country
 */
class TaxRuleCountryHistoryTransformer extends BaseTransformer
{
    /**
     * @param TaxRuleCountryHistory $taxRuleCountryHistory
     *
     * @return array
     */
    public function transform(TaxRuleCountryHistory $taxRuleCountryHistory) : array
    {
        return [
            'id'            => $taxRuleCountryHistory->_id,
            'from_tax_rate' => $taxRuleCountryHistory->from_tax_rate,
            'to_tax_rate'   => $taxRuleCountryHistory->to_tax_rate,
            'from_date'     => $taxRuleCountryHistory->from_date ?
                $taxRuleCountryHistory->from_date->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'to_date'       => $taxRuleCountryHistory->to_date ?
                $taxRuleCountryHistory->to_date->format('Y-m-d H:i:s') :
                null
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'tax_rule_country_history';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'tax_rule_country_history';
    }
}
