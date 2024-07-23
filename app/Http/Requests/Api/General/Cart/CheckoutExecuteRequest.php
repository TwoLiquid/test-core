<?php

namespace App\Http\Requests\Api\General\Cart;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class CheckoutExecuteRequest
 *
 * @package App\Http\Requests\Api\General\Cart
 */
class CheckoutExecuteRequest extends BaseRequest
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
            'order_id.required' => trans('validations/api/general/cart/checkoutExecute.order_id.required'),
            'order_id.integer'  => trans('validations/api/general/cart/checkoutExecute.order_id.integer'),
            'order_id.exists'   => trans('validations/api/general/cart/checkoutExecute.order_id.exists'),
            'hash.required'     => trans('validations/api/general/cart/checkoutExecute.hash.required'),
            'hash.string'       => trans('validations/api/general/cart/checkoutExecute.hash.string')
        ];
    }
}
