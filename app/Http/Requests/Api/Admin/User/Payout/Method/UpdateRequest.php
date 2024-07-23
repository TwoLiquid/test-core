<?php

namespace App\Http\Requests\Api\Admin\User\Payout\Method;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 * 
 * @package App\Http\Requests\Api\Admin\User\Payout\Method
 */
class UpdateRequest extends BaseRequest
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
            'method_id.required'      => trans('validations/api/admin/user/payout/method/update.method_id.required'),
            'method_id.integer'       => trans('validations/api/admin/user/payout/method/update.method_id.integer'),
            'method_id.exists'        => trans('validations/api/admin/user/payout/method/update.method_id.exists'),
            'fields.required'         => trans('validations/api/admin/user/payout/method/update.fields.required'),
            'fields.array'            => trans('validations/api/admin/user/payout/method/update.fields.array'),
            'fields.*.id.required'    => trans('validations/api/admin/user/payout/method/update.fields.*.id.required'),
            'fields.*.id.integer'     => trans('validations/api/admin/user/payout/method/update.fields.*.id.integer'),
            'fields.*.id.exists'      => trans('validations/api/admin/user/payout/method/update.fields.*.id.exists'),
            'fields.*.value.required' => trans('validations/api/admin/user/payout/method/update.fields.*.value.required')
        ];
    }
}
