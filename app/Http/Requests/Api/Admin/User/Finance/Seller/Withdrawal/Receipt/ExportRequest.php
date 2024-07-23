<?php

namespace App\Http\Requests\Api\Admin\User\Finance\Seller\Withdrawal\Receipt;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ExportRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Finance\Seller\Withdrawal\Receipt
 */
class ExportRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'receipt_id',
        'request_id',
        'date',
        'payout_method',
        'total',
        'payout_status'
    ];

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'id'                     => 'required|integer|exists:users,id',
            'type'                   => 'required|string|in:xls,pdf',
            'receipt_id'             => 'integer|exists:withdrawal_receipts,id|nullable',
            'request_id'             => 'string|nullable',
            'date_from'              => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'payout_method_id'       => 'integer|exists:payment_methods,id|nullable',
            'receipt_statuses_ids'   => 'array|nullable',
            'receipt_statuses_ids.*' => 'required|integer|in:1,2,3|nullable',
            'sort_by'                => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'             => 'string|in:desc,asc|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'id.required'                    => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.id.required'),
            'id.integer'                     => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.id.integer'),
            'id.exists'                      => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.id.exists'),
            'type.required'                  => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.type.required'),
            'type.string'                    => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.type.string'),
            'type.in'                        => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.type.in'),
            'receipt_id.integer'             => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.receipt_id.integer'),
            'receipt_id.exists'              => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.receipt_id.exists'),
            'request_id.string'              => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.request_id.string'),
            'date_from.string'               => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.date_from.string'),
            'date_from.date_format'          => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.date_from.date_format'),
            'date_to.string'                 => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.date_to.string'),
            'date_to.date_format'            => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.date_to.date_format'),
            'payout_method_id.integer'       => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.payout_method_id.integer'),
            'payout_method_id.exists'        => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.payout_method_id.exists'),
            'receipt_statuses_ids.array'     => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.receipt_statuses_ids.array'),
            'receipt_statuses_ids.*.integer' => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.receipt_statuses_ids.*.integer'),
            'receipt_statuses_ids.*.in'      => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.receipt_statuses_ids.*.in'),
            'sort_by.string'                 => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.sort_by.string'),
            'sort_by.in'                     => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.sort_by.in'),
            'sort_order.string'              => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.sort_order.string'),
            'sort_order.in'                  => trans('validations/api/admin/user/finance/seller/withdrawal/receipt/export.sort_order.in')
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
            'id'                   => isset($params['id']) ? (int) $params['id'] : null,
            'receipt_id'           => isset($params['receipt_id']) ? (int) $params['receipt_id'] : null,
            'payout_method_id'     => isset($params['payout_method_id']) ? (int) $params['payout_method_id'] : null,
            'receipt_statuses_ids' => isset($params['receipt_statuses_ids']) ? explodeUrlIds($params['receipt_statuses_ids']) : null
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
