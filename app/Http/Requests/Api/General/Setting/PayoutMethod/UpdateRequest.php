<?php

namespace App\Http\Requests\Api\General\Setting\PayoutMethod;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\Setting\PayoutMethod
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'fields'         => 'required|array',
            'fields.*.id'    => 'required|integer|exists:payment_method_fields,id',
            'fields.*.value' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'fields.required'         => trans('validations/api/general/setting/payout/method/update.fields.required'),
            'fields.array'            => trans('validations/api/general/setting/payout/method/update.fields.array'),
            'fields.*.id.required'    => trans('validations/api/general/setting/payout/method/update.fields.*.id.required'),
            'fields.*.id.integer'     => trans('validations/api/general/setting/payout/method/update.fields.*.id.integer'),
            'fields.*.id.exists'      => trans('validations/api/general/setting/payout/method/update.fields.*.id.exists'),
            'fields.*.value.required' => trans('validations/api/general/setting/payout/method/update.fields.*.value.required')
        ];
    }
}
