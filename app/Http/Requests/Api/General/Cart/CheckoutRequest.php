<?php

namespace App\Http\Requests\Api\General\Cart;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class CheckoutRequest
 *
 * @package App\Http\Requests\Api\General\Cart
 */
class CheckoutRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'method_id' => 'required|integer|exists:payment_methods,id'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'method_id.required' => trans('validations/api/general/cart/checkout.method_id.required'),
            'method_id.integer'  => trans('validations/api/general/cart/checkout.method_id.integer'),
            'method_id.exists'   => trans('validations/api/general/cart/checkout.method_id.exists')
        ];
    }
}
