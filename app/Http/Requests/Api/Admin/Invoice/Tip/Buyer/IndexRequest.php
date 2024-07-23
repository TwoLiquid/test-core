<?php

namespace App\Http\Requests\Api\Admin\Invoice\Tip\Buyer;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\Invoice\Tip\Buyer
 */
class IndexRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'order_item_id',
        'vybe_type',
        'buyer',
        'seller',
        'date',
        'order_item_status',
        'tip_invoice_id',
        'tip_amount',
        'status'
    ];

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'item_id'                   => 'integer|nullable',
            'vybe_type_id'              => 'integer|between:1,3|nullable',
            'buyer'                     => 'string|nullable',
            'seller'                    => 'string|nullable',
            'date_from'                 => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                   => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'order_item_statuses_ids'   => 'array|nullable',
            'order_item_statuses_ids.*' => 'required|integer|between:1,10',
            'invoice_id'                => 'integer|nullable',
            'invoice_statuses_ids'      => 'array|nullable',
            'invoice_statuses_ids.*'    => 'required|integer|in:1,2,3,4,5,6',
            'sort_by'                   => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'                => 'string|in:desc,asc|nullable',
            'paginated'                 => 'boolean|nullable',
            'per_page'                  => 'integer|nullable',
            'page'                      => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'item_id.integer'                   => trans('validations/api/admin/invoice/tip/buyer/index.item_id.integer'),
            'vybe_type_id.integer'              => trans('validations/api/admin/invoice/tip/buyer/index.vybe_type_id.integer'),
            'vybe_type_id.between'              => trans('validations/api/admin/invoice/tip/buyer/index.vybe_type_id.between'),
            'buyer.string'                      => trans('validations/api/admin/invoice/tip/buyer/index.buyer.string'),
            'seller.string'                     => trans('validations/api/admin/invoice/tip/buyer/index.seller.string'),
            'date_from.string'                  => trans('validations/api/admin/invoice/tip/buyer/index.date_from.string'),
            'date_from.date_format'             => trans('validations/api/admin/invoice/tip/buyer/index.date_from.date_format'),
            'date_to.string'                    => trans('validations/api/admin/invoice/tip/buyer/index.date_to.string'),
            'date_to.date_format'               => trans('validations/api/admin/invoice/tip/buyer/index.date_to.date_format'),
            'order_item_statuses_ids.array'     => trans('validations/api/admin/invoice/tip/buyer/index.order_item_statuses_ids.array'),
            'order_item_statuses_ids.*.integer' => trans('validations/api/admin/invoice/tip/buyer/index.order_item_statuses_ids.*.integer'),
            'order_item_statuses_ids.*.between' => trans('validations/api/admin/invoice/tip/buyer/index.order_item_statuses_ids.*.between'),
            'invoice_id.integer'                => trans('validations/api/admin/invoice/tip/buyer/index.invoice_id.integer'),
            'invoice_statuses_ids.array'        => trans('validations/api/admin/invoice/tip/buyer/index.invoice_statuses_ids.array'),
            'invoice_statuses_ids.*.integer'    => trans('validations/api/admin/invoice/tip/buyer/index.invoice_statuses_ids.*.integer'),
            'invoice_statuses_ids.*.in'         => trans('validations/api/admin/invoice/tip/buyer/index.invoice_statuses_ids.*.in'),
            'sort_by.string'                    => trans('validations/api/admin/invoice/tip/buyer/index.sort_by.string'),
            'sort_by.in'                        => trans('validations/api/admin/invoice/tip/buyer/index.sort_by.in'),
            'sort_order.string'                 => trans('validations/api/admin/invoice/tip/buyer/index.sort_order.string'),
            'sort_order.in'                     => trans('validations/api/admin/invoice/tip/buyer/index.sort_order.in'),
            'paginated.boolean'                 => trans('validations/api/admin/invoice/tip/buyer/index.paginated.boolean'),
            'per_page.integer'                  => trans('validations/api/admin/invoice/tip/buyer/index.per_page.integer'),
            'page.integer'                      => trans('validations/api/admin/invoice/tip/buyer/index.page.integer')
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
            'item_id'                 => isset($params['item_id']) ? (int) $params['item_id'] : null,
            'vybe_type_id'            => isset($params['vybe_type_id']) ? (int) $params['vybe_type_id'] : null,
            'order_item_statuses_ids' => isset($params['order_item_statuses_ids']) ? explodeUrlIds($params['order_item_statuses_ids']) : null,
            'invoice_id'              => isset($params['invoice_id']) ? (int) $params['invoice_id'] : null,
            'invoice_statuses_ids'    => isset($params['invoice_statuses_ids']) ? explodeUrlIds($params['invoice_statuses_ids']) : null,
            'paginated'               => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'per_page'                => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'                    => isset($params['page']) ? (int) $params['page'] : null
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
