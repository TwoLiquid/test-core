<?php

namespace App\Http\Requests\Api\General\Dashboard\Sale;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Sale
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'vybe_title'                 => 'string|nullable',
            'username'                   => 'string|nullable',
            'appearance_id'              => 'integer|between:1,3|nullable',
            'vybe_type_id'               => 'integer|between:1,3|nullable',
            'activity_id'                => 'integer|exists:activities,id|nullable',
            'datetime_from'              => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'datetime_to'                => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'amount_from'                => 'integer|nullable',
            'amount_to'                  => 'integer|nullable',
            'quantity'                   => 'integer|nullable',
            'order_item_status_id'       => 'integer|between:1,10|nullable',
            'only_open'                  => 'boolean|nullable',
            'order_item_sale_sort_by_id' => 'integer|between:1,4|nullable',
            'order_item_sale_sort_order' => 'string|in:asc,desc|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'vybe_title.string'                  => trans('validations/api/general/dashboard/sale/index.vybe_title.string'),
            'username.string'                    => trans('validations/api/general/dashboard/sale/index.username.string'),
            'appearance_id.integer'              => trans('validations/api/general/dashboard/sale/index.appearance_id.integer'),
            'appearance_id.between'              => trans('validations/api/general/dashboard/sale/index.appearance_id.between'),
            'vybe_type_id.integer'               => trans('validations/api/general/dashboard/sale/index.vybe_type_id.integer'),
            'vybe_type_id.between'               => trans('validations/api/general/dashboard/sale/index.vybe_type_id.between'),
            'activity_id.integer'                => trans('validations/api/general/dashboard/sale/index.vybe_type_id.integer'),
            'activity_id.exists'                 => trans('validations/api/general/dashboard/sale/index.activity_id.exists'),
            'datetime_from.date_format'          => trans('validations/api/general/dashboard/sale/index.datetime_from.date_format'),
            'datetime_to.date_format'            => trans('validations/api/general/dashboard/sale/index.datetime_to.date_format'),
            'amount_from.integer'                => trans('validations/api/general/dashboard/sale/index.amount_from.integer'),
            'amount_to.integer'                  => trans('validations/api/general/dashboard/sale/index.amount_to.integer'),
            'quantity.integer'                   => trans('validations/api/general/dashboard/sale/index.quantity.integer'),
            'order_item_status_id.integer'       => trans('validations/api/general/dashboard/sale/index.order_item_status_id.integer'),
            'order_item_status_id.between'       => trans('validations/api/general/dashboard/sale/index.order_item_status_id.between'),
            'only_open.boolean'                  => trans('validations/api/general/dashboard/sale/index.only_open.boolean'),
            'order_item_sale_sort_by_id.integer' => trans('validations/api/general/dashboard/sale/index.order_item_sale_sort_by_id.integer'),
            'order_item_sale_sort_by_id.between' => trans('validations/api/general/dashboard/sale/index.order_item_sale_sort_by_id.between'),
            'order_item_sale_sort_order.string'  => trans('validations/api/general/dashboard/sale/index.order_item_sale_sort_order.string'),
            'order_item_sale_sort_order.in'      => trans('validations/api/general/dashboard/sale/index.order_item_sale_sort_order.in'),
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
            'appearance_id'              => isset($params['appearance_id']) ? (int) $params['appearance_id'] : null,
            'vybe_type_id'               => isset($params['vybe_type_id']) ? (int) $params['vybe_type_id'] : null,
            'activity_id'                => isset($params['activity_id']) ? (int) $params['activity_id'] : null,
            'amount_from'                => isset($params['amount_from']) ? (int) $params['amount_from'] : null,
            'amount_to'                  => isset($params['amount_to']) ? (int) $params['amount_to'] : null,
            'quantity'                   => isset($params['quantity']) ? (int) $params['quantity'] : null,
            'order_item_status_id'       => isset($params['order_item_status_id']) ? (int) $params['order_item_status_id'] : null,
            'only_open'                  => isset($params['only_open']) ? (bool) $params['only_open'] : null,
            'order_item_sale_sort_by_id' => isset($params['order_item_sale_sort_by_id']) ? (int) $params['order_item_sale_sort_by_id'] : null
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
