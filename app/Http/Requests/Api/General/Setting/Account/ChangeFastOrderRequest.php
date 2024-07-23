<?php

namespace App\Http\Requests\Api\General\Setting\Account;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ChangeFastOrderRequest
 *
 * @package App\Http\Requests\Api\General\Setting\Account
 */
class ChangeFastOrderRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'enable_fast_order' => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'enable_fast_order.required' => trans('validations/api/general/setting/account/changeFastOrder/enable_fast_order.required'),
            'enable_fast_order.boolean'  => trans('validations/api/general/setting/account/changeFastOrder/enable_fast_order.boolean')
        ];
    }
}
