<?php

namespace App\Http\Requests\Api\Admin\Invoice\Withdrawal\Receipt;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\Invoice\Withdrawal\Receipt
 */
class IndexRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'receipt_id',
        'request_id',
        'date',
        'client',
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
            'receipt_id'             => 'integer|exists:withdrawal_receipts,id|nullable',
            'request_id'             => 'string|nullable',
            'date_from'              => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'client'                 => 'string|nullable',
            'payout_method_id'       => 'integer|exists:payment_methods,id|nullable',
            'receipt_statuses_ids'   => 'array|nullable',
            'receipt_statuses_ids.*' => 'required|integer|in:1,2,3|nullable',
            'sort_by'                => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'             => 'string|in:desc,asc|nullable',
            'paginated'              => 'boolean|nullable',
            'per_page'               => 'integer|nullable',
            'page'                   => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'receipt_id.integer'             => trans('validations/api/admin/invoice/withdrawal/receipt/index.receipt_id.integer'),
            'receipt_id.exists'              => trans('validations/api/admin/invoice/withdrawal/receipt/index.receipt_id.exists'),
            'request_id.string'              => trans('validations/api/admin/invoice/withdrawal/receipt/index.request_id.string'),
            'date_from.string'               => trans('validations/api/admin/invoice/withdrawal/receipt/index.date_from.string'),
            'date_from.date_format'          => trans('validations/api/admin/invoice/withdrawal/receipt/index.date_from.date_format'),
            'date_to.string'                 => trans('validations/api/admin/invoice/withdrawal/receipt/index.date_to.string'),
            'date_to.date_format'            => trans('validations/api/admin/invoice/withdrawal/receipt/index.date_to.date_format'),
            'client.string'                  => trans('validations/api/admin/invoice/withdrawal/receipt/index.client.string'),
            'payout_method_id.integer'       => trans('validations/api/admin/invoice/withdrawal/receipt/index.payout_method_id.integer'),
            'payout_method_id.exists'        => trans('validations/api/admin/invoice/withdrawal/receipt/index.payout_method_id.exists'),
            'receipt_statuses_ids.array'     => trans('validations/api/admin/invoice/withdrawal/receipt/index.receipt_statuses_ids.array'),
            'receipt_statuses_ids.*.integer' => trans('validations/api/admin/invoice/withdrawal/receipt/index.receipt_statuses_ids.*.integer'),
            'receipt_statuses_ids.*.in'      => trans('validations/api/admin/invoice/withdrawal/receipt/index.receipt_statuses_ids.*.in'),
            'sort_by.string'                 => trans('validations/api/admin/invoice/withdrawal/receipt/index.sort_by.string'),
            'sort_by.in'                     => trans('validations/api/admin/invoice/withdrawal/receipt/index.sort_by.in'),
            'sort_order.string'              => trans('validations/api/admin/invoice/withdrawal/receipt/index.sort_order.string'),
            'sort_order.in'                  => trans('validations/api/admin/invoice/withdrawal/receipt/index.sort_order.in'),
            'paginated.boolean'              => trans('validations/api/admin/invoice/withdrawal/receipt/index.paginated.boolean'),
            'per_page.integer'               => trans('validations/api/admin/invoice/withdrawal/receipt/index.per_page.integer'),
            'page.integer'                   => trans('validations/api/admin/invoice/withdrawal/receipt/index.page.integer')
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
            'receipt_id'           => isset($params['receipt_id']) ? (int) $params['receipt_id'] : null,
            'payout_method_id'     => isset($params['payout_method_id']) ? (int) $params['payout_method_id'] : null,
            'receipt_statuses_ids' => isset($params['receipt_statuses_ids']) ? explodeUrlIds($params['receipt_statuses_ids']) : null,
            'paginated'            => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'per_page'             => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'                 => isset($params['page']) ? (int) $params['page'] : null
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
