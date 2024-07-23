<?php

namespace App\Http\Requests\Api\General\Tip;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\General\Tip
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'item_id'   => 'required|integer|exists:order_items,id',
            'method_id' => 'required|integer|exists:payment_methods,id',
            'amount'    => 'required|integer',
            'comment'   => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'item_id.required'   => trans('validations/api/general/tip/store.item_id.required'),
            'item_id.integer'    => trans('validations/api/general/tip/store.item_id.integer'),
            'item_id.exists'     => trans('validations/api/general/tip/store.item_id.exists'),
            'method_id.required' => trans('validations/api/general/tip/store.method_id.required'),
            'method_id.integer'  => trans('validations/api/general/tip/store.method_id.integer'),
            'method_id.exists'   => trans('validations/api/general/tip/store.method_id.exists'),
            'amount.required'    => trans('validations/api/general/tip/store.amount.required'),
            'amount.integer'     => trans('validations/api/general/tip/store.amount.integer'),
            'comment.string'     => trans('validations/api/general/tip/store.comment.string')
        ];
    }
}
