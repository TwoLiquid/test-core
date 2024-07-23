<?php

namespace App\Http\Requests\Api\Admin\Order\OrderItem\FinishRequest;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\Order\OrderItem\FinishRequest
 */
class IndexRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'order_item_id',
        'from_request_datetime',
        'to_request_datetime',
        'waiting',
        'vybe_title',
        'order_date',
        'buyer',
        'seller',
        'initiator',
        'from_order_item_statuses',
        'to_order_item_statuses',
        'order_item_request_action'
    ];

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'order_item_id'                   => 'integer|exists:order_items,id|nullable',
            'from_request_datetime'           => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'to_request_datetime'             => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'vybe_title'                      => 'string|nullable',
            'order_date_from'                 => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'order_date_to'                   => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'buyer'                           => 'string|nullable',
            'seller'                          => 'string|nullable',
            'from_order_item_statuses_ids'    => 'array|nullable',
            'from_order_item_statuses_ids.*'  => 'integer|between:1,10|nullable',
            'to_order_item_statuses_ids'      => 'array|nullable',
            'to_order_item_statuses_ids.*'    => 'integer|between:1,10|nullable',
            'order_item_request_action_ids'   => 'array|nullable',
            'order_item_request_action_ids.*' => 'integer|between:1,4|nullable',
            'sort_by'                         => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'                      => 'string|in:desc,asc|nullable',
            'paginated'                       => 'boolean|nullable',
            'per_page'                        => 'integer|nullable',
            'page'                            => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'order_item_id.integer'                   => trans('validations/api/admin/order/orderItem/finishRequest/index.order_item_id.integer'),
            'order_item_id.exists'                    => trans('validations/api/admin/order/orderItem/finishRequest/index.order_item_id.exists'),
            'from_request_datetime.string'            => trans('validations/api/admin/order/orderItem/finishRequest/index.from_request_datetime.string'),
            'from_request_datetime.date_format'       => trans('validations/api/admin/order/orderItem/finishRequest/index.from_request_datetime.date_format'),
            'to_request_datetime.string'              => trans('validations/api/admin/order/orderItem/finishRequest/index.to_request_datetime.string'),
            'to_request_datetime.date_format'         => trans('validations/api/admin/order/orderItem/finishRequest/index.to_request_datetime.date_format'),
            'vybe_title.string'                       => trans('validations/api/admin/order/orderItem/finishRequest/index.vybe_title.string'),
            'order_date_from.string'                  => trans('validations/api/admin/order/orderItem/finishRequest/index.order_date_from.string'),
            'order_date_from.date_format'             => trans('validations/api/admin/order/orderItem/finishRequest/index.order_date_from.date_format'),
            'order_date_to.string'                    => trans('validations/api/admin/order/orderItem/finishRequest/index.order_date_to.string'),
            'order_date_to.date_format'               => trans('validations/api/admin/order/orderItem/finishRequest/index.order_date_to.date_format'),
            'buyer.string'                            => trans('validations/api/admin/order/orderItem/finishRequest/index.buyer.string'),
            'seller.string'                           => trans('validations/api/admin/order/orderItem/finishRequest/index.seller.string'),
            'from_order_item_statuses_ids.array'      => trans('validations/api/admin/order/orderItem/finishRequest/index.from_order_item_statuses_ids.array'),
            'from_order_item_statuses_ids.*.integer'  => trans('validations/api/admin/order/orderItem/finishRequest/index.from_order_item_statuses_ids.*.integer'),
            'from_order_item_statuses_ids.*.between'  => trans('validations/api/admin/order/orderItem/finishRequest/index.from_order_item_statuses_ids.*.between'),
            'to_order_item_statuses_ids.array'        => trans('validations/api/admin/order/orderItem/finishRequest/index.to_order_item_statuses_ids.array'),
            'to_order_item_statuses_ids.*.integer'    => trans('validations/api/admin/order/orderItem/finishRequest/index.to_order_item_statuses_ids.*.integer'),
            'to_order_item_statuses_ids.*.between'    => trans('validations/api/admin/order/orderItem/finishRequest/index.to_order_item_statuses_ids.*.between'),
            'order_item_request_action_ids.array'     => trans('validations/api/admin/order/orderItem/finishRequest/index.order_item_request_action_ids.array'),
            'order_item_request_action_ids.*.integer' => trans('validations/api/admin/order/orderItem/finishRequest/index.order_item_request_action_ids.*.integer'),
            'order_item_request_action_ids.*.between' => trans('validations/api/admin/order/orderItem/finishRequest/index.order_item_request_action_ids.*.between'),
            'sort_by.string'                          => trans('validations/api/admin/order/orderItem/finishRequest/index.sort_by.string'),
            'sort_by.in'                              => trans('validations/api/admin/order/orderItem/finishRequest/index.sort_by.in'),
            'sort_order.string'                       => trans('validations/api/admin/order/orderItem/finishRequest/index.sort_order.string'),
            'sort_order.in'                           => trans('validations/api/admin/order/orderItem/finishRequest/index.sort_order.in'),
            'paginated.boolean'                       => trans('validations/api/admin/order/orderItem/finishRequest/index.paginated.boolean'),
            'per_page.integer'                        => trans('validations/api/admin/order/orderItem/finishRequest/index.per_page.integer'),
            'page.integer'                            => trans('validations/api/admin/order/orderItem/finishRequest/index.page.integer')
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
            'order_item_id'                 => isset($params['order_item_id']) ? (int) $params['order_item_id'] : null,
            'from_order_item_statuses_ids'  => isset($params['from_order_item_statuses_ids']) ? explodeUrlIds($params['from_order_item_statuses_ids']) : null,
            'to_order_item_statuses_ids'    => isset($params['to_order_item_statuses_ids']) ? explodeUrlIds($params['to_order_item_statuses_ids']) : null,
            'order_item_request_action_ids' => isset($params['order_item_request_action_ids']) ? explodeUrlIds($params['order_item_request_action_ids']) : null,
            'paginated'                     => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'per_page'                      => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'                          => isset($params['page']) ? (int) $params['page'] : null,
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
