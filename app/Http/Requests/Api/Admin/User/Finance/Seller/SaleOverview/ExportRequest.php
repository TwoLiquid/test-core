<?php

namespace App\Http\Requests\Api\Admin\User\Finance\Seller\SaleOverview;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ExportRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Finance\Seller\SaleOverview
 */
class ExportRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'sale_overview_id',
        'order_date',
        'buyer',
        'order_item_id',
        'total',
        'vybe_type',
        'order_item_payment_status'
    ];

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'id'                                => 'required|integer|exists:users,id',
            'type'                              => 'required|string|in:xls,pdf',
            'overview_id'                       => 'integer|exists:sales,id|nullable',
            'date_from'                         => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                           => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'buyer'                             => 'string|nullable',
            'order_item_id'                     => 'integer|exists:order_items,id|nullable',
            'vybe_types_ids'                    => 'array|nullable',
            'vybe_types_ids.*'                  => 'integer|between:1,3|nullable',
            'order_item_payment_statuses_ids'   => 'array|nullable',
            'order_item_payment_statuses_ids.*' => 'integer|between:1,6|nullable',
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
            'id.required'                               => trans('validations/api/admin/user/finance/seller/saleOverview/export.id.required'),
            'id.integer'                                => trans('validations/api/admin/user/finance/seller/saleOverview/export.id.integer'),
            'id.exists'                                 => trans('validations/api/admin/user/finance/seller/saleOverview/export.id.exists'),
            'type.required'                             => trans('validations/api/admin/user/finance/seller/saleOverview/export.type.required'),
            'type.string'                               => trans('validations/api/admin/user/finance/seller/saleOverview/export.type.string'),
            'type.in'                                   => trans('validations/api/admin/user/finance/seller/saleOverview/export.type.in'),
            'overview_id.integer'                       => trans('validations/api/admin/user/finance/seller/saleOverview/export.overview_id.integer'),
            'overview_id.exists'                        => trans('validations/api/admin/user/finance/seller/saleOverview/export.overview_id.exists'),
            'date_from.string'                          => trans('validations/api/admin/user/finance/seller/saleOverview/export.date_from.string'),
            'date_from.date_format'                     => trans('validations/api/admin/user/finance/seller/saleOverview/export.date_from.date_format'),
            'date_to.string'                            => trans('validations/api/admin/user/finance/seller/saleOverview/export.date_to.string'),
            'date_to.date_format'                       => trans('validations/api/admin/user/finance/seller/saleOverview/export.date_to.date_format'),
            'buyer.string'                              => trans('validations/api/admin/user/finance/seller/saleOverview/export.buyer.string'),
            'order_item_id.integer'                     => trans('validations/api/admin/user/finance/seller/saleOverview/export.order_item_id.integer'),
            'order_item_id.exists'                      => trans('validations/api/admin/user/finance/seller/saleOverview/export.order_item_id.exists'),
            'vybe_types_ids.array'                      => trans('validations/api/admin/user/finance/seller/saleOverview/export.vybe_types_ids.array'),
            'vybe_types_ids.*.integer'                  => trans('validations/api/admin/user/finance/seller/saleOverview/export.vybe_types_ids.*.integer'),
            'vybe_types_ids.*.between'                  => trans('validations/api/admin/user/finance/seller/saleOverview/export.vybe_types_ids.*.between'),
            'order_item_payment_statuses_ids.array'     => trans('validations/api/admin/user/finance/seller/saleOverview/export.order_item_payment_statuses_ids.array'),
            'order_item_payment_statuses_ids.*.integer' => trans('validations/api/admin/user/finance/seller/saleOverview/export.order_item_payment_statuses_ids.*.integer'),
            'order_item_payment_statuses_ids.*.between' => trans('validations/api/admin/user/finance/seller/saleOverview/export.order_item_payment_statuses_ids.*.between'),
            'sort_by.string'                            => trans('validations/api/admin/user/finance/seller/saleOverview/export.sort_by.string'),
            'sort_by.in'                                => trans('validations/api/admin/user/finance/seller/saleOverview/export.sort_by.in'),
            'sort_order.string'                         => trans('validations/api/admin/user/finance/seller/saleOverview/export.sort_order.string'),
            'sort_order.in'                             => trans('validations/api/admin/user/finance/seller/saleOverview/export.sort_order.in')
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
            'id'                              => isset($params['id']) ? (int) $params['id'] : null,
            'overview_id'                     => isset($params['overview_id']) ? (int) $params['overview_id'] : null,
            'order_item_id'                   => isset($params['order_item_id']) ? (int) $params['order_item_id'] : null,
            'vybe_types_ids'                  => isset($params['vybe_types_ids']) ? explodeUrlIds($params['vybe_types_ids']) : null,
            'order_item_payment_statuses_ids' => isset($params['order_item_payment_statuses_ids']) ? explodeUrlIds($params['order_item_payment_statuses_ids']) : null
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
