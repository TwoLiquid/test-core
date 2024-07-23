<?php

namespace App\Http\Requests\Api\Admin\User\Finance\Seller\OrderItem;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ExportRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Finance\Seller\OrderItem
 */
class ExportRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'order_item_id',
        'order_date',
        'buyer',
        'total',
        'vybe_version',
        'vybe_type',
        'order_item_status',
        'order_item_payment_status',
        'invoice_status',
    ];

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'id'                                => 'required|integer|exists:users,id',
            'type'                              => 'required|string|in:xls,pdf',
            'order_item_id'                     => 'integer|exists:order_items,id|nullable',
            'date_from'                         => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                           => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'buyer'                             => 'string|nullable',
            'vybe_version'                      => 'integer|nullable',
            'vybe_types_ids'                    => 'array|nullable',
            'vybe_types_ids.*'                  => 'integer|between:1,3|nullable',
            'order_item_statuses_ids'           => 'array|nullable',
            'order_item_statuses_ids.*'         => 'integer|between:1,10|nullable',
            'order_item_payment_statuses_ids'   => 'array|nullable',
            'order_item_payment_statuses_ids.*' => 'integer|between:1,6|nullable',
            'invoice_statuses_ids'              => 'array|nullable',
            'invoice_statuses_ids.*'            => 'integer|in:2,3,4,5,6|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'id.required'                               => trans('validations/api/admin/user/finance/buyer/orderItem/export.id.required'),
            'id.integer'                                => trans('validations/api/admin/user/finance/buyer/orderItem/export.id.integer'),
            'id.exists'                                 => trans('validations/api/admin/user/finance/buyer/orderItem/export.id.exists'),
            'type.required'                             => trans('validations/api/admin/user/finance/buyer/orderItem/export.type.required'),
            'type.string'                               => trans('validations/api/admin/user/finance/buyer/orderItem/export.type.string'),
            'type.in'                                   => trans('validations/api/admin/user/finance/buyer/orderItem/export.type.in'),
            'order_item_id.integer'                     => trans('validations/api/admin/user/finance/buyer/orderItem/export.order_item_id.integer'),
            'order_item_id.exists'                      => trans('validations/api/admin/user/finance/buyer/orderItem/export.order_item_id.exists'),
            'date_from.string'                          => trans('validations/api/admin/user/finance/buyer/orderItem/export.date_from.string'),
            'date_from.date_format'                     => trans('validations/api/admin/user/finance/buyer/orderItem/export.date_from.date_format'),
            'date_to.string'                            => trans('validations/api/admin/user/finance/buyer/orderItem/export.date_to.string'),
            'date_to.date_format'                       => trans('validations/api/admin/user/finance/buyer/orderItem/export.date_to.date_format'),
            'buyer.string'                              => trans('validations/api/admin/user/finance/buyer/orderItem/export.buyer.string'),
            'vybe_version.integer'                      => trans('validations/api/admin/user/finance/buyer/orderItem/export.vybe_version.integer'),
            'vybe_types_ids.array'                      => trans('validations/api/admin/user/finance/buyer/orderItem/export.vybe_types_ids.array'),
            'vybe_types_ids.*.integer'                  => trans('validations/api/admin/user/finance/buyer/orderItem/export.vybe_types_ids.*.integer'),
            'vybe_types_ids.*.between'                  => trans('validations/api/admin/user/finance/buyer/orderItem/export.vybe_types_ids.*.between'),
            'order_item_statuses_ids.array'             => trans('validations/api/admin/user/finance/buyer/orderItem/export.order_item_statuses_ids.array'),
            'order_item_statuses_ids.*.integer'         => trans('validations/api/admin/user/finance/buyer/orderItem/export.order_item_statuses_ids.*.integer'),
            'order_item_statuses_ids.*.between'         => trans('validations/api/admin/user/finance/buyer/orderItem/export.order_item_statuses_ids.*.between'),
            'order_item_payment_statuses_ids.array'     => trans('validations/api/admin/user/finance/buyer/orderItem/export.order_item_payment_statuses_ids.array'),
            'order_item_payment_statuses_ids.*.integer' => trans('validations/api/admin/user/finance/buyer/orderItem/export.order_item_payment_statuses_ids.*.integer'),
            'order_item_payment_statuses_ids.*.between' => trans('validations/api/admin/user/finance/buyer/orderItem/export.order_item_payment_statuses_ids.*.between'),
            'invoice_statuses_ids.array'                => trans('validations/api/admin/user/finance/buyer/orderItem/export.invoice_statuses_ids.array'),
            'invoice_statuses_ids.*.integer'            => trans('validations/api/admin/user/finance/buyer/orderItem/export.invoice_statuses_ids.*.integer'),
            'invoice_statuses_ids.*.in'                 => trans('validations/api/admin/user/finance/buyer/orderItem/export.invoice_statuses_ids.*.in')
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
            'order_item_id'                   => isset($params['order_item_id']) ? (int) $params['order_item_id'] : null,
            'vybe_version'                    => isset($params['vybe_version']) ? (int) $params['vybe_version'] : null,
            'vybe_types_ids'                  => isset($params['vybe_types_ids']) ? explodeUrlIds($params['vybe_types_ids']) : null,
            'order_item_statuses_ids'         => isset($params['order_item_statuses_ids']) ? explodeUrlIds($params['order_item_statuses_ids']) : null,
            'order_item_payment_statuses_ids' => isset($params['order_item_payment_statuses_ids']) ? explodeUrlIds($params['order_item_payment_statuses_ids']) : null,
            'invoice_statuses_ids'            => isset($params['invoice_statuses_ids']) ? explodeUrlIds($params['invoice_statuses_ids']) : null
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
