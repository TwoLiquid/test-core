<?php

namespace App\Http\Requests\Api\General\Tip;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\General\Tip
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'item_id'   => 'required|integer|exists:order_items,id',
            'method_id' => 'integer|exists:payment_methods,id|nullable',
            'amount'    => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'item_id.required'  => trans('validations/api/general/tip/index.item_id.required'),
            'item_id.integer'   => trans('validations/api/general/tip/index.item_id.integer'),
            'item_id.exists'    => trans('validations/api/general/tip/index.item_id.exists'),
            'method_id.integer' => trans('validations/api/general/tip/index.method_id.integer'),
            'method_id.exists'  => trans('validations/api/general/tip/index.method_id.exists'),
            'amount.integer'    => trans('validations/api/general/tip/index.amount.integer')
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation() : void
    {
        $params = $this->all();

        $this->merge([
            'item_id'   => isset($params['item_id']) ? (int) $params['item_id'] : null,
            'method_id' => isset($params['method_id']) ? (int) $params['method_id'] : null,
            'amount'    => isset($params['amount']) ? (int) $params['amount'] : null
        ]);
    }

    /**
     * @param null $keys
     *
     * @return array
     */
    public function all($keys = null) : array
    {
        return array_merge(
            parent::all(),
            $this->route()->parameters()
        );
    }
}
