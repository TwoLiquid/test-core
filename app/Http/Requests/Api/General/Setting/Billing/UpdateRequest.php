<?php

namespace App\Http\Requests\Api\General\Setting\Billing;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\Setting\Billing
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'first_name'                  => 'string|nullable',
            'last_name'                   => 'string|nullable',
            'country_place_id'            => 'required|string|exists:country_places,place_id',
            'region_place_id'             => 'string|exists:region_places,place_id|nullable',
            'city'                        => 'string|nullable',
            'postal_code'                 => 'string|nullable',
            'address'                     => 'string|nullable',
            'phone_code_country_place_id' => 'string|exists:country_places,place_id|nullable',
            'phone'                       => 'string|nullable',
            'business_info'               => 'required|boolean',
            'company_name'                => 'string|nullable',
            'vat_number'                  => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'first_name.string'                  => trans('validations/api/general/setting/billing/update.first_name.string'),
            'last_name.string'                   => trans('validations/api/general/setting/billing/update.last_name.string'),
            'country_place_id.required'          => trans('validations/api/general/setting/billing/update.country_place_id.required'),
            'country_place_id.string'            => trans('validations/api/general/setting/billing/update.country_place_id.string'),
            'country_place_id.exists'            => trans('validations/api/general/setting/billing/update.country_place_id.exists'),
            'region_place_id.string'             => trans('validations/api/general/setting/billing/update.region_place_id.string'),
            'region_place_id.exists'             => trans('validations/api/general/setting/billing/update.region_place_id.exists'),
            'city.string'                        => trans('validations/api/general/setting/billing/update.city.string'),
            'postal_code.string'                 => trans('validations/api/general/setting/billing/update.postal_code.string'),
            'address.string'                     => trans('validations/api/general/setting/billing/update.address.string'),
            'phone_code_country_place_id.string' => trans('validations/api/general/setting/billing/update.phone_code_country_place_id.string'),
            'phone_code_country_place_id.exists' => trans('validations/api/general/setting/billing/update.phone_code_country_place_id.exists'),
            'phone.string'                       => trans('validations/api/general/setting/billing/update.phone.string'),
            'business_info.required'             => trans('validations/api/general/setting/billing/update.business_info.required'),
            'business_info.string'               => trans('validations/api/general/setting/billing/update.business_info.string'),
            'company_name.string'                => trans('validations/api/general/setting/billing/update.company_name.string'),
            'vat_number.string'                  => trans('validations/api/general/setting/billing/update.vat_number.string')
        ];
    }
}
