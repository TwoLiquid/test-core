<?php

namespace App\Http\Requests\Api\Admin\User\Finance\Buyer\OrderItem;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Finance\Buyer\OrderItem
 */
class IndexRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'order_item_id',
        'order_date',
        'seller',
        'total',
        'vybe_version',
        'vybe_type',
        'order_item_status',
        'order_item_payment_status'
    ];

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'order_item_id'                     => 'integer|exists:order_items,id|nullable',
            'date_from'                         => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                           => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'seller'                            => 'string|nullable',
            'vybe_version'                      => 'integer|nullable',
            'vybe_types_ids'                    => 'array|nullable',
            'vybe_types_ids.*'                  => 'integer|between:1,3|nullable',
            'order_item_statuses_ids'           => 'array|nullable',
            'order_item_statuses_ids.*'         => 'integer|between:1,10|nullable',
            'order_item_payment_statuses_ids'   => 'array|nullable',
            'order_item_payment_statuses_ids.*' => 'integer|between:1,6|nullable',
            'sort_by'                           => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'                        => 'string|in:desc,asc|nullable',
            'paginated'                         => 'boolean|nullable',
            'per_page'                          => 'integer|nullable',
            'page'                              => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'order_item_id.integer'                     => trans('validations/api/admin/user/finance/buyer/orderItem/index.order_item_id.integer'),
            'order_item_id.exists'                      => trans('validations/api/admin/user/finance/buyer/orderItem/index.order_item_id.exists'),
            'date_from.string'                          => trans('validations/api/admin/user/finance/buyer/orderItem/index.date_from.string'),
            'date_from.date_format'                     => trans('validations/api/admin/user/finance/buyer/orderItem/index.date_from.date_format'),
            'date_to.string'                            => trans('validations/api/admin/user/finance/buyer/orderItem/index.date_to.string'),
            'date_to.date_format'                       => trans('validations/api/admin/user/finance/buyer/orderItem/index.date_to.date_format'),
            'seller.string'                             => trans('validations/api/admin/user/finance/buyer/orderItem/index.seller.string'),
            'vybe_version.integer'                      => trans('validations/api/admin/user/finance/buyer/orderItem/index.vybe_version.integer'),
            'vybe_types_ids.array'                      => trans('validations/api/admin/user/finance/buyer/orderItem/index.vybe_types_ids.array'),
            'vybe_types_ids.*.integer'                  => trans('validations/api/admin/user/finance/buyer/orderItem/index.vybe_types_ids.*.integer'),
            'vybe_types_ids.*.between'                  => trans('validations/api/admin/user/finance/buyer/orderItem/index.vybe_types_ids.*.between'),
            'order_item_statuses_ids.array'             => trans('validations/api/admin/user/finance/buyer/orderItem/index.order_item_statuses_ids.array'),
            'order_item_statuses_ids.*.integer'         => trans('validations/api/admin/user/finance/buyer/orderItem/index.order_item_statuses_ids.*.integer'),
            'order_item_statuses_ids.*.between'         => trans('validations/api/admin/user/finance/buyer/orderItem/index.order_item_statuses_ids.*.between'),
            'order_item_payment_statuses_ids.array'     => trans('validations/api/admin/user/finance/buyer/orderItem/index.order_item_payment_statuses_ids.array'),
            'order_item_payment_statuses_ids.*.integer' => trans('validations/api/admin/user/finance/buyer/orderItem/index.order_item_payment_statuses_ids.*.integer'),
            'order_item_payment_statuses_ids.*.between' => trans('validations/api/admin/user/finance/buyer/orderItem/index.order_item_payment_statuses_ids.*.between'),
            'sort_by.string'                            => trans('validations/api/admin/user/finance/buyer/orderItem/index.sort_by.string'),
            'sort_by.in'                                => trans('validations/api/admin/user/finance/buyer/orderItem/index.sort_by.in'),
            'sort_order.string'                         => trans('validations/api/admin/user/finance/buyer/orderItem/index.sort_order.string'),
            'sort_order.in'                             => trans('validations/api/admin/user/finance/buyer/orderItem/index.sort_order.in'),
            'paginated.boolean'                         => trans('validations/api/admin/user/finance/buyer/orderItem/index.paginated.boolean'),
            'per_page.integer'                          => trans('validations/api/admin/user/finance/buyer/orderItem/index.per_page.integer'),
            'page.integer'                              => trans('validations/api/admin/user/finance/buyer/orderItem/index.page.integer')
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
            'order_item_id'                   => isset($params['order_item_id']) ? (int) $params['order_item_id'] : null,
            'vybe_version'                    => isset($params['vybe_version']) ? (int) $params['vybe_version'] : null,
            'vybe_types_ids'                  => isset($params['vybe_types_ids']) ? explodeUrlIds($params['vybe_types_ids']) : null,
            'order_item_statuses_ids'         => isset($params['order_item_statuses_ids']) ? explodeUrlIds($params['order_item_statuses_ids']) : null,
            'order_item_payment_statuses_ids' => isset($params['order_item_payment_statuses_ids']) ? explodeUrlIds($params['order_item_payment_statuses_ids']) : null,
            'paginated'                       => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'per_page'                        => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'                            => isset($params['page']) ? (int) $params['page'] : null,
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
