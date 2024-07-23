<?php

namespace App\Http\Requests\Api\Admin\General\TaxRule\Region;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\General\TaxRule\Region
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'region_code' => 'string|nullable',
            'tax_rate'    => 'required|numeric',
            'from_date'   => 'required|string|date_format:Y-m-d\TH:i:s.v\Z'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'region_code.required'      => trans('validations/api/admin/general/taxRule/region/update.region_code.required'),
            'region_code.numeric'       => trans('validations/api/admin/general/taxRule/region/update.region_code.numeric'),
            'tax_rate.required'         => trans('validations/api/admin/general/taxRule/region/update.tax_rate.required'),
            'tax_rate.numeric'          => trans('validations/api/admin/general/taxRule/region/update.tax_rate.numeric'),
            'from_date.required'        => trans('validations/api/admin/general/taxRule/region/update.from_date.required'),
            'from_date.string'          => trans('validations/api/admin/general/taxRule/region/update.from_date.string'),
            'from_date.date_format'     => trans('validations/api/admin/general/taxRule/region/update.from_date.date_format')
        ];
    }
}
