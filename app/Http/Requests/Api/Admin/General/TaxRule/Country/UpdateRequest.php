<?php

namespace App\Http\Requests\Api\Admin\General\TaxRule\Country;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\General\TaxRule\Country
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'tax_rate'  => 'required|numeric',
            'from_date' => 'required|string|date_format:Y-m-d\TH:i:s.v\Z'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'tax_rate.required'     => trans('validations/api/admin/general/taxRule/country/update.tax_rate.required'),
            'tax_rate.numeric'      => trans('validations/api/admin/general/taxRule/country/update.tax_rate.numeric'),
            'from_date.required'    => trans('validations/api/admin/general/taxRule/country/update.from_date.required'),
            'from_date.string'      => trans('validations/api/admin/general/taxRule/country/update.from_date.string'),
            'from_date.date_format' => trans('validations/api/admin/general/taxRule/country/update.from_date.date_format')
        ];
    }
}
