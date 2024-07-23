<?php

namespace App\Http\Requests\Api\Admin\Invoice\AddFunds\Receipt;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ExportRequest
 *
 * @package App\Http\Requests\Api\Admin\Invoice\AddFunds\Receipt
 */
class ExportRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'receipt_id',
        'date',
        'buyer',
        'payment_method',
        'amount',
        'payment_fee',
        'total_amount',
        'payment_status'
    ];

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'type'           => 'required|string|in:xls,pdf',
            'receipt_id'     => 'integer|exists:add_funds_receipts,id|nullable',
            'date_from'      => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'        => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'buyer'          => 'string|nullable',
            'method_id'      => 'integer|exists:payment_methods,id|nullable',
            'statuses_ids'   => 'array|nullable',
            'statuses_ids.*' => 'required|integer|between:1,4|nullable',
            'sort_by'        => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'     => 'string|in:desc,asc|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'type.required'          => trans('validations/api/admin/invoice/addFunds/receipt/export.type.required'),
            'type.string'            => trans('validations/api/admin/invoice/addFunds/receipt/export.type.string'),
            'type.in'                => trans('validations/api/admin/invoice/addFunds/receipt/export.type.in'),
            'receipt_id.integer'     => trans('validations/api/admin/invoice/addFunds/receipt/export.receipt_id.integer'),
            'receipt_id.exists'      => trans('validations/api/admin/invoice/addFunds/receipt/export.receipt_id.exists'),
            'date_from.string'       => trans('validations/api/admin/invoice/addFunds/receipt/export.date_from.string'),
            'date_from.date_format'  => trans('validations/api/admin/invoice/addFunds/receipt/export.date_from.date_format'),
            'date_to.string'         => trans('validations/api/admin/invoice/addFunds/receipt/export.date_to.string'),
            'date_to.date_format'    => trans('validations/api/admin/invoice/addFunds/receipt/export.date_to.date_format'),
            'buyer.string'           => trans('validations/api/admin/invoice/addFunds/receipt/export.buyer.string'),
            'method_id.integer'      => trans('validations/api/admin/invoice/addFunds/receipt/export.method_id.integer'),
            'method_id.exists'       => trans('validations/api/admin/invoice/addFunds/receipt/export.method_id.exists'),
            'statuses_ids.array'     => trans('validations/api/admin/invoice/addFunds/receipt/export.statuses_ids.array'),
            'statuses_ids.*.integer' => trans('validations/api/admin/invoice/addFunds/receipt/export.statuses_ids.*.integer'),
            'statuses_ids.*.in'      => trans('validations/api/admin/invoice/addFunds/receipt/export.statuses_ids.*.in'),
            'sort_by.string'         => trans('validations/api/admin/invoice/addFunds/receipt/export.sort_by.string'),
            'sort_by.in'             => trans('validations/api/admin/invoice/addFunds/receipt/export.sort_by.in'),
            'sort_order.string'      => trans('validations/api/admin/invoice/addFunds/receipt/export.sort_order.string'),
            'sort_order.in'          => trans('validations/api/admin/invoice/addFunds/receipt/export.sort_order.in')
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
            'receipt_id'   => isset($params['receipt_id']) ? (int) $params['receipt_id'] : null,
            'method_id'    => isset($params['method_id']) ? (int) $params['method_id'] : null,
            'statuses_ids' => isset($params['statuses_ids']) ? explodeUrlIds($params['statuses_ids']) : null
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
