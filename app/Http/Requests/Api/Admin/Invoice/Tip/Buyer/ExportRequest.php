<?php

namespace App\Http\Requests\Api\Admin\Invoice\Tip\Buyer;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ExportRequest
 *
 * @package App\Http\Requests\Api\Admin\Invoice\Tip\Buyer
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
            'type'                      => 'required|string|in:xls,pdf',
            'item_id'                   => 'integer|exists:order_items,id|nullable',
            'vybe_type_id'              => 'integer|between:1,3|nullable',
            'buyer'                     => 'string|nullable',
            'seller'                    => 'string|nullable',
            'date_from'                 => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                   => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'order_item_statuses_ids'   => 'array|nullable',
            'order_item_statuses_ids.*' => 'required|integer|between:1,6',
            'invoice_id'                => 'integer|exists:tip_invoices,id|nullable',
            'invoice_statuses_ids'      => 'array|nullable',
            'invoice_statuses_ids.*'    => 'required|integer|in:1,2,3,4,5,6',
            'sort_by'                   => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'                => 'string|in:desc,asc|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'type.required'                     => trans('validations/api/admin/invoice/tip/buyer/export.type.required'),
            'type.string'                       => trans('validations/api/admin/invoice/tip/buyer/export.type.string'),
            'type.in'                           => trans('validations/api/admin/invoice/tip/buyer/export.type.in'),
            'item_id.integer'                   => trans('validations/api/admin/invoice/tip/buyer/export.item_id.integer'),
            'item_id.exists'                    => trans('validations/api/admin/invoice/tip/buyer/export.item_id.exists'),
            'vybe_type_id.integer'              => trans('validations/api/admin/invoice/tip/buyer/export.vybe_type_id.integer'),
            'vybe_type_id.between'              => trans('validations/api/admin/invoice/tip/buyer/export.vybe_type_id.between'),
            'buyer.string'                      => trans('validations/api/admin/invoice/tip/buyer/export.buyer.string'),
            'seller.string'                     => trans('validations/api/admin/invoice/tip/buyer/export.seller.string'),
            'date_from.string'                  => trans('validations/api/admin/invoice/tip/buyer/export.date_from.string'),
            'date_from.date_format'             => trans('validations/api/admin/invoice/tip/buyer/export.date_from.date_format'),
            'date_to.string'                    => trans('validations/api/admin/invoice/tip/buyer/export.date_to.string'),
            'date_to.date_format'               => trans('validations/api/admin/invoice/tip/buyer/export.date_to.date_format'),
            'order_item_statuses_ids.array'     => trans('validations/api/admin/invoice/tip/buyer/export.order_item_statuses_ids.array'),
            'order_item_statuses_ids.*.integer' => trans('validations/api/admin/invoice/tip/buyer/export.order_item_statuses_ids.*.integer'),
            'order_item_statuses_ids.*.between' => trans('validations/api/admin/invoice/tip/buyer/export.order_item_statuses_ids.*.between'),
            'invoice_id.integer'                => trans('validations/api/admin/invoice/tip/buyer/export.invoice_id.integer'),
            'invoice_id.exists'                 => trans('validations/api/admin/invoice/tip/buyer/export.invoice_id.exists'),
            'invoice_statuses_ids.array'        => trans('validations/api/admin/invoice/tip/buyer/export.invoice_statuses_ids.array'),
            'invoice_statuses_ids.*.integer'    => trans('validations/api/admin/invoice/tip/buyer/export.invoice_statuses_ids.*.integer'),
            'invoice_statuses_ids.*.in'         => trans('validations/api/admin/invoice/tip/buyer/export.invoice_statuses_ids.*.in'),
            'sort_by.string'                    => trans('validations/api/admin/invoice/tip/buyer/export.sort_by.string'),
            'sort_by.in'                        => trans('validations/api/admin/invoice/tip/buyer/export.sort_by.in'),
            'sort_order.string'                 => trans('validations/api/admin/invoice/tip/buyer/export.sort_order.string'),
            'sort_order.in'                     => trans('validations/api/admin/invoice/tip/buyer/export.sort_order.in')
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
            'invoice_statuses_ids'    => isset($params['invoice_statuses_ids']) ? explodeUrlIds($params['invoice_statuses_ids']) : null
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
