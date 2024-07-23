<?php

namespace App\Http\Requests\Api\Admin\Order\Tip;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ExportRequest
 *
 * @package App\Http\Requests\Api\Admin\Order\Tip
 */
class ExportRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'order_item_id',
        'vybe_type',
        'buyer',
        'seller',
        'payment_method',
        'order_item_status',
        'tip_invoice_buyer_id',
        'tip_invoice_buyer_status',
        'date',
        'tip_amount',
        'handling_fee',
        'tip_invoice_seller_id',
        'tip_invoice_seller_status',
        'status'
    ];

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'type'                              => 'required|string|in:xls,pdf',
            'item_id'                           => 'integer|exists:order_items,id|nullable',
            'vybe_types_ids'                    => 'array|nullable',
            'vybe_types_ids.*'                  => 'required|integer|between:1,3',
            'buyer'                             => 'string|nullable',
            'seller'                            => 'string|nullable',
            'payment_methods_ids'               => 'array|nullable',
            'payment_methods_ids.*'             => 'required|integer|exists:payment_methods,id',
            'order_item_statuses_ids'           => 'array|nullable',
            'order_item_statuses_ids.*'         => 'required|integer|between:1,6',
            'tip_invoice_buyer_id'              => 'integer|exists:tip_invoices,id|nullable',
            'tip_invoice_buyer_statuses_ids'    => 'array|nullable',
            'tip_invoice_buyer_statuses_ids.*'  => 'required|integer',
            'date_from'                         => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                           => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'tip_invoice_seller_id'             => 'integer|exists:tip_invoices,id|nullable',
            'tip_invoice_seller_statuses_ids'   => 'array|nullable',
            'tip_invoice_seller_statuses_ids.*' => 'required|integer',
            'sort_by'                           => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'                        => 'string|in:desc,asc|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'type.required'                             => trans('validations/api/admin/order/tip/export.type.required'),
            'type.string'                               => trans('validations/api/admin/order/tip/export.type.string'),
            'type.in'                                   => trans('validations/api/admin/order/tip/export.type.in'),
            'item_id.integer'                           => trans('validations/api/admin/order/tip/export.item_id.integer'),
            'item_id.exists'                            => trans('validations/api/admin/order/tip/export.item_id.exists'),
            'vybe_types_ids.array'                      => trans('validations/api/admin/order/tip/export.vybe_types_ids.array'),
            'vybe_types_ids.*.integer'                  => trans('validations/api/admin/order/tip/export.vybe_types_ids.*.integer'),
            'vybe_types_ids.*.between'                  => trans('validations/api/admin/order/tip/export.vybe_types_ids.*.between'),
            'buyer.string'                              => trans('validations/api/admin/order/tip/export.buyer.string'),
            'seller.string'                             => trans('validations/api/admin/order/tip/export.seller.string'),
            'payment_methods_ids.array'                 => trans('validations/api/admin/order/tip/export.payment_methods_ids.array'),
            'payment_methods_ids.*.integer'             => trans('validations/api/admin/order/tip/export.payment_methods_ids.*.integer'),
            'payment_methods_ids.*.exists'              => trans('validations/api/admin/order/tip/export.payment_methods_ids.*.exists'),
            'order_item_statuses_ids.array'             => trans('validations/api/admin/order/tip/export.order_item_statuses_ids.array'),
            'order_item_statuses_ids.*.integer'         => trans('validations/api/admin/order/tip/export.order_item_statuses_ids.*.integer'),
            'order_item_statuses_ids.*.between'         => trans('validations/api/admin/order/tip/export.order_item_statuses_ids.*.between'),
            'tip_invoice_buyer_id.integer'              => trans('validations/api/admin/order/tip/export.tip_invoice_buyer_id.integer'),
            'tip_invoice_buyer_id.exists'               => trans('validations/api/admin/order/tip/export.tip_invoice_buyer_id.exists'),
            'tip_invoice_buyer_statuses_ids.array'      => trans('validations/api/admin/order/tip/export.tip_invoice_buyer_statuses_ids.array'),
            'tip_invoice_buyer_statuses_ids.*.integer'  => trans('validations/api/admin/order/tip/export.tip_invoice_buyer_statuses_ids.*.integer'),
            'tip_invoice_buyer_statuses_ids.*.in'       => trans('validations/api/admin/order/tip/export.tip_invoice_buyer_statuses_ids.*.in'),
            'date_from.string'                          => trans('validations/api/admin/order/tip/export.date_from.string'),
            'date_from.date_format'                     => trans('validations/api/admin/order/tip/export.date_from.date_format'),
            'date_to.string'                            => trans('validations/api/admin/order/tip/export.date_to.string'),
            'date_to.date_format'                       => trans('validations/api/admin/order/tip/export.date_to.date_format'),
            'tip_invoice_seller_id.integer'             => trans('validations/api/admin/order/tip/export.tip_invoice_seller_id.integer'),
            'tip_invoice_seller_id.exists'              => trans('validations/api/admin/order/tip/export.tip_invoice_seller_id.exists'),
            'tip_invoice_seller_statuses_ids.array'     => trans('validations/api/admin/order/tip/export.tip_invoice_seller_statuses_ids.array'),
            'tip_invoice_seller_statuses_ids.*.integer' => trans('validations/api/admin/order/tip/export.tip_invoice_seller_statuses_ids.*.integer'),
            'tip_invoice_seller_statuses_ids.*.in'      => trans('validations/api/admin/order/tip/export.tip_invoice_seller_statuses_ids.*.in'),
            'sort_by.string'                            => trans('validations/api/admin/order/tip/export.sort_by.string'),
            'sort_by.in'                                => trans('validations/api/admin/order/tip/export.sort_by.in'),
            'sort_order.string'                         => trans('validations/api/admin/order/tip/export.sort_order.string'),
            'sort_order.in'                             => trans('validations/api/admin/order/tip/export.sort_order.in')
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
            'item_id'                         => isset($params['item_id']) ? (int) $params['item_id'] : null,
            'vybe_types_ids'                  => isset($params['vybe_types_ids']) ? explodeUrlIds($params['vybe_types_ids']) : null,
            'payment_methods_ids'             => isset($params['payment_methods_ids']) ? explodeUrlIds($params['payment_methods_ids']) : null,
            'order_item_statuses_ids'         => isset($params['order_item_statuses_ids']) ? explodeUrlIds($params['order_item_statuses_ids']) : null,
            'tip_invoice_buyer_id'            => isset($params['tip_invoice_buyer_id']) ? (int) $params['tip_invoice_buyer_id'] : null,
            'tip_invoice_buyer_statuses_ids'  => isset($params['tip_invoice_buyer_statuses_ids']) ? explodeUrlIds($params['tip_invoice_buyer_statuses_ids']) : null,
            'tip_invoice_seller_id'           => isset($params['tip_invoice_seller_id']) ? (int) $params['tip_invoice_seller_id'] : null,
            'tip_invoice_seller_statuses_ids' => isset($params['tip_invoice_seller_statuses_ids']) ? explodeUrlIds($params['tip_invoice_seller_statuses_ids']) : null
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
