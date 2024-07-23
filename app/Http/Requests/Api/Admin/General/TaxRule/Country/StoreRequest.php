<?php

namespace App\Http\Requests\Api\Admin\General\TaxRule\Country;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\General\TaxRule\Country
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'country_place_id' => 'required|string|exists:country_places,place_id',
            'tax_rate'         => 'required|numeric',
            'from_date'        => 'required|string|date_format:Y-m-d\TH:i:s.v\Z'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'country_place_id.required' => trans('validations/api/admin/general/taxRule/country/store.country_place_id.required'),
            'country_place_id.string'   => trans('validations/api/admin/general/taxRule/country/store.country_place_id.string'),
            'country_place_id.exists'   => trans('validations/api/admin/general/taxRule/country/store.country_place_id.exists'),
            'tax_rate.required'         => trans('validations/api/admin/general/taxRule/country/store.tax_rate.required'),
            'tax_rate.numeric'          => trans('validations/api/admin/general/taxRule/country/store.tax_rate.numeric'),
            'from_date.required'        => trans('validations/api/admin/general/taxRule/country/store.from_date.required'),
            'from_date.string'          => trans('validations/api/admin/general/taxRule/country/store.from_date.string'),
            'from_date.date_format'     => trans('validations/api/admin/general/taxRule/country/store.from_date.date_format')
        ];
    }
}
