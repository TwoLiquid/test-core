<?php

namespace App\Http\Requests\Api\Admin\Invoice\Seller;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ExportRequest
 *
 * @package App\Http\Requests\Api\Admin\Invoice\Seller
 */
class ExportRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'invoice_id',
        'date',
        'vybe_version',
        'seller',
        'buyer',
        'total',
        'handling_fee',
        'earned',
        'vybe_type',
        'order_item_payment_status',
        'invoice_status'
    ];

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'type'                      => 'required|string|in:xls,pdf',
            'invoice_id'                => 'integer|exists:order_invoices,id|nullable',
            'date_from'                 => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                   => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'vybe_version'              => 'integer|nullable',
            'buyer'                     => 'string|nullable',
            'seller'                    => 'string|nullable',
            'vybe_types_ids'            => 'array|nullable',
            'vybe_types_ids.*'          => 'required|integer|between:1,3|nullable',
            'order_item_statuses_ids'   => 'array|nullable',
            'order_item_statuses_ids.*' => 'required|integer|between:1,10|nullable',
            'invoice_statuses_ids'      => 'array|nullable',
            'invoice_statuses_ids.*'    => 'required|integer|in:2,3,4,5,6|nullable',
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
            'type.required'                     => trans('validations/api/admin/invoice/seller/export.type.required'),
            'type.string'                       => trans('validations/api/admin/invoice/seller/export.type.string'),
            'type.in'                           => trans('validations/api/admin/invoice/seller/export.type.in'),
            'invoice_id.integer'                => trans('validations/api/admin/invoice/seller/export.invoice_id.integer'),
            'invoice_id.exists'                 => trans('validations/api/admin/invoice/seller/export.invoice_id.exists'),
            'date_from.string'                  => trans('validations/api/admin/invoice/seller/export.date_from.string'),
            'date_from.date_format'             => trans('validations/api/admin/invoice/seller/export.date_from.date_format'),
            'date_to.string'                    => trans('validations/api/admin/invoice/seller/export.date_to.string'),
            'date_to.date_format'               => trans('validations/api/admin/invoice/seller/export.date_to.date_format'),
            'vybe_version.integer'              => trans('validations/api/admin/invoice/seller/export.vybe_version.integer'),
            'buyer.string'                      => trans('validations/api/admin/invoice/seller/export.buyer.string'),
            'seller.string'                     => trans('validations/api/admin/invoice/seller/export.seller.string'),
            'vybe_types_ids.array'              => trans('validations/api/admin/invoice/seller/export.vybe_types_ids.array'),
            'vybe_types_ids.*.integer'          => trans('validations/api/admin/invoice/seller/export.vybe_types_ids.*.integer'),
            'vybe_types_ids.*.between'          => trans('validations/api/admin/invoice/seller/export.vybe_types_ids.*.between'),
            'order_item_statuses_ids.array'     => trans('validations/api/admin/invoice/seller/export.order_item_statuses_ids.array'),
            'order_item_statuses_ids.*.integer' => trans('validations/api/admin/invoice/seller/export.order_item_statuses_ids.*.integer'),
            'order_item_statuses_ids.*.between' => trans('validations/api/admin/invoice/seller/export.order_item_statuses_ids.*.between'),
            'invoice_statuses_ids.array'        => trans('validations/api/admin/invoice/seller/export.invoice_statuses_ids.array'),
            'invoice_statuses_ids.*.integer'    => trans('validations/api/admin/invoice/seller/export.invoice_statuses_ids.*.integer'),
            'invoice_statuses_ids.*.in'         => trans('validations/api/admin/invoice/seller/export.invoice_statuses_ids.*.in'),
            'sort_by.string'                    => trans('validations/api/admin/invoice/seller/export.sort_by.string'),
            'sort_by.in'                        => trans('validations/api/admin/invoice/seller/export.sort_by.in'),
            'sort_order.string'                 => trans('validations/api/admin/invoice/seller/export.sort_order.string'),
            'sort_order.in'                     => trans('validations/api/admin/invoice/seller/export.sort_order.in')
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
            'invoice_id'              => isset($params['invoice_id']) ? (int) $params['invoice_id'] : null,
            'vybe_version'            => isset($params['vybe_version']) ? (int) $params['vybe_version'] : null,
            'vybe_types_ids'          => isset($params['vybe_types_ids']) ? explodeUrlIds($params['vybe_types_ids']) : null,
            'order_item_statuses_ids' => isset($params['order_item_statuses_ids']) ? explodeUrlIds($params['order_item_statuses_ids']) : null,
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
