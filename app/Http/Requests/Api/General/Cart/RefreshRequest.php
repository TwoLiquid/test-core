<?php

namespace App\Http\Requests\Api\General\Cart;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class RefreshRequest
 *
 * @package App\Http\Requests\Api\General\Cart
 */
class RefreshRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'with_form'                       => 'boolean|nullable',
            'cart_items'                      => 'array|sometimes',
            'cart_items.*.appearance_case_id' => 'required|integer|exists:appearance_cases,id',
            'cart_items.*.datetime_from'      => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'cart_items.*.datetime_to'        => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'cart_items.*.quantity'           => 'required|integer'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'with_form.boolean'                        => trans('validations/api/general/cart/refresh.with_form.boolean'),
            'cart_items.required'                      => trans('validations/api/general/cart/refresh.cart_items.required'),
            'cart_items.array'                         => trans('validations/api/general/cart/refresh.cart_items.array'),
            'cart_items.*.appearance_case_id.required' => trans('validations/api/general/cart/refresh.cart_items.*.appearance_case_id.required'),
            'cart_items.*.appearance_case_id.integer'  => trans('validations/api/general/cart/refresh.cart_items.*.appearance_case_id.integer'),
            'cart_items.*.appearance_case_id.exists'   => trans('validations/api/general/cart/refresh.cart_items.*.appearance_case_id.exists'),
            'cart_items.*.datetime_from.date_format'   => trans('validations/api/general/cart/refresh.cart_items.*.datetime_from.date_format'),
            'cart_items.*.datetime_to.date_format'     => trans('validations/api/general/cart/refresh.cart_items.*.datetime_to.date_format'),
            'cart_items.*.quantity.required'           => trans('validations/api/general/cart/refresh.cart_items.*.quantity.required'),
            'cart_items.*.quantity.integer'            => trans('validations/api/general/cart/refresh.cart_items.*.quantity.integer')
        ];
    }
}
