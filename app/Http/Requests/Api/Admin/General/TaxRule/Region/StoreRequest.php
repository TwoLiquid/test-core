<?php

namespace App\Http\Requests\Api\Admin\General\TaxRule\Region;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\General\TaxRule\Region
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'tax_rule_country_id' => 'required|integer|exists:tax_rule_countries,id',
            'region_place_id'     => 'required|string',
            'region_code'         => 'string|nullable',
            'tax_rate'            => 'required|numeric',
            'from_date'           => 'required|string|date_format:Y-m-d\TH:i:s.v\Z'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'tax_rule_country_id.required' => trans('validations/api/admin/general/taxRule/region/store.tax_rule_country_id.required'),
            'tax_rule_country_id.integer'  => trans('validations/api/admin/general/taxRule/region/store.tax_rule_country_id.integer'),
            'tax_rule_country_id.exists'   => trans('validations/api/admin/general/taxRule/region/store.tax_rule_country_id.exists'),
            'region_place_id.required'     => trans('validations/api/admin/general/taxRule/region/store.region_place_id.required'),
            'region_place_id.string'       => trans('validations/api/admin/general/taxRule/region/store.region_place_id.string'),
            'region_place_id.exists'       => trans('validations/api/admin/general/taxRule/region/store.region_place_id.exists'),
            'region_code.required'         => trans('validations/api/admin/general/taxRule/region/store.region_code.required'),
            'region_code.numeric'          => trans('validations/api/admin/general/taxRule/region/store.region_code.numeric'),
            'tax_rate.required'            => trans('validations/api/admin/general/taxRule/region/store.tax_rate.required'),
            'tax_rate.numeric'             => trans('validations/api/admin/general/taxRule/region/store.tax_rate.numeric'),
            'from_date.required'           => trans('validations/api/admin/general/taxRule/region/store.from_date.required'),
            'from_date.string'             => trans('validations/api/admin/general/taxRule/region/store.from_date.string'),
            'from_date.date_format'        => trans('validations/api/admin/general/taxRule/region/store.from_date.date_format')
        ];
    }
}
