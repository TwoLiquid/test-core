<?php

namespace App\Http\Requests\Api\Admin\User\Billing;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 * 
 * @package App\Http\Requests\Api\Admin\User\Billing
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'first_name'                             => 'string|nullable',
            'last_name'                              => 'string|nullable',
            'country_place_id'                       => 'required|string|exists:country_places,place_id',
            'region_place_id'                        => 'string|exists:region_places,place_id|nullable',
            'city'                                   => 'string|nullable',
            'postal_code'                            => 'string|nullable',
            'address'                                => 'string|nullable',
            'phone_code_country_place_id'            => 'string|exists:country_places,place_id|nullable',
            'phone'                                  => 'string|nullable',
            'company_name'                           => 'string|nullable',
            'vat_number'                             => 'string|nullable',
            'vat_number_proof_files'                 => 'array|nullable',
            'vat_number_proof_files.*.content'       => 'string|nullable',
            'vat_number_proof_files.*.original_name' => 'string|nullable',
            'vat_number_proof_files.*.extension'     => 'string|nullable',
            'vat_number_proof_files.*.mime'          => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'first_name.string'                             => trans('validations/api/admin/user/billing/update.first_name.string'),
            'last_name.string'                              => trans('validations/api/admin/user/billing/update.last_name.string'),
            'country_place_id.required'                     => trans('validations/api/admin/user/billing/update.country_place_id.required'),
            'country_place_id.string'                       => trans('validations/api/admin/user/billing/update.country_place_id.string'),
            'country_place_id.exists'                       => trans('validations/api/admin/user/billing/update.country_place_id.exists'),
            'region_place_id.string'                        => trans('validations/api/admin/user/billing/update.region_place_id.string'),
            'region_place_id.exists'                        => trans('validations/api/admin/user/billing/update.region_place_id.exists'),
            'city.string'                                   => trans('validations/api/admin/user/billing/update.city.string'),
            'postal_code.string'                            => trans('validations/api/admin/user/billing/update.postal_code.string'),
            'address.string'                                => trans('validations/api/admin/user/billing/update.address.string'),
            'phone_code_country_place_id.string'            => trans('validations/api/admin/user/billing/update.phone_code_country_place_id.string'),
            'phone_code_country_place_id.exists'            => trans('validations/api/admin/user/billing/update.phone_code_country_place_id.exists'),
            'phone.string'                                  => trans('validations/api/admin/user/billing/update.phone.string'),
            'company_name.string'                           => trans('validations/api/admin/user/billing/update.company_name.string'),
            'vat_number.string'                             => trans('validations/api/admin/user/billing/update.vat_number.string'),
            'vat_number_proof_files.array'                  => trans('validations/api/admin/user/billing/update.vat_number_proof_files.array'),
            'vat_number_proof_files.*.content.string'       => trans('validations/api/admin/user/billing/update.vat_number_proof_files.*.content.string'),
            'vat_number_proof_files.*.original_name.string' => trans('validations/api/admin/user/billing/update.vat_number_proof_files.*.original_name.string'),
            'vat_number_proof_files.*.mime.string'          => trans('validations/api/admin/user/billing/update.vat_number_proof_files.*.mime.string'),
            'vat_number_proof_files.*.extension.string'     => trans('validations/api/admin/user/billing/update.vat_number_proof_files.*.extension.string')
        ];
    }
}
