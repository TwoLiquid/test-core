<?php

namespace App\Http\Requests\Api\General\Cart;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class CheckoutCancelRequest
 *
 * @package App\Http\Requests\Api\General\Cart
 */
class CheckoutCancelRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'order_id' => 'required|integer|exists:orders,id',
            'hash'     => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'order_id.required' => trans('validations/api/general/cart/checkoutCancel.order_id.required'),
            'order_id.integer'  => trans('validations/api/general/cart/checkoutCancel.order_id.integer'),
            'order_id.exists'   => trans('validations/api/general/cart/checkoutCancel.order_id.exists'),
            'hash.required'     => trans('validations/api/general/cart/checkoutCancel.hash.required'),
            'hash.string'       => trans('validations/api/general/cart/checkoutCancel.hash.string')
        ];
    }
}
