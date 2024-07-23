<?php

namespace App\Http\Requests\Api\General\Setting\PayoutMethod;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\General\Setting\PayoutMethod
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'method_id'      => 'required|integer|exists:payment_methods,id',
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
            'method_id.required'      => trans('validations/api/general/setting/payout/method/store.method_id.required'),
            'method_id.integer'       => trans('validations/api/general/setting/payout/method/store.method_id.integer'),
            'method_id.exists'        => trans('validations/api/general/setting/payout/method/store.method_id.exists'),
            'fields.required'         => trans('validations/api/general/setting/payout/method/store.fields.required'),
            'fields.array'            => trans('validations/api/general/setting/payout/method/store.fields.array'),
            'fields.*.id.required'    => trans('validations/api/general/setting/payout/method/store.fields.*.id.required'),
            'fields.*.id.integer'     => trans('validations/api/general/setting/payout/method/store.fields.*.id.integer'),
            'fields.*.id.exists'      => trans('validations/api/general/setting/payout/method/store.fields.*.id.exists'),
            'fields.*.value.required' => trans('validations/api/general/setting/payout/method/store.fields.*.value.required')
        ];
    }
}
