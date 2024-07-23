<?php

namespace App\Http\Requests\Api\Admin\User\Finance\Buyer\AddFunds\Receipt;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Finance\Buyer\AddFunds\Receipt
 */
class IndexRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'receipt_id',
        'date',
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
            'receipt_id'     => 'integer|exists:add_funds_receipts,id|nullable',
            'date_from'      => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'        => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'method_id'      => 'integer|exists:payment_methods,id|nullable',
            'statuses_ids'   => 'array|nullable',
            'statuses_ids.*' => 'required|integer|between:1,4|nullable',
            'sort_by'        => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'     => 'string|in:desc,asc|nullable',
            'paginated'      => 'boolean|nullable',
            'per_page'       => 'integer|nullable',
            'page'           => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'receipt_id.integer'     => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.receipt_id.integer'),
            'receipt_id.exists'      => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.receipt_id.exists'),
            'date_from.string'       => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.date_from.string'),
            'date_from.date_format'  => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.date_from.date_format'),
            'date_to.string'         => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.date_to.string'),
            'date_to.date_format'    => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.date_to.date_format'),
            'method_id.integer'      => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.method_id.integer'),
            'method_id.exists'       => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.method_id.exists'),
            'statuses_ids.array'     => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.statuses_ids.array'),
            'statuses_ids.*.integer' => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.statuses_ids.*.integer'),
            'statuses_ids.*.in'      => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.statuses_ids.*.in'),
            'sort_by.string'         => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.sort_by.string'),
            'sort_by.in'             => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.sort_by.in'),
            'sort_order.string'      => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.sort_order.string'),
            'sort_order.in'          => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.sort_order.in'),
            'paginated.boolean'      => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.paginated.boolean'),
            'per_page.integer'       => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.per_page.integer'),
            'page.integer'           => trans('validations/api/admin/user/finance/buyer/addFunds/receipt/index.page.integer')
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
            'statuses_ids' => isset($params['statuses_ids']) ? explodeUrlIds($params['statuses_ids']) : null,
            'paginated'    => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'per_page'     => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'         => isset($params['page']) ? (int) $params['page'] : null
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
