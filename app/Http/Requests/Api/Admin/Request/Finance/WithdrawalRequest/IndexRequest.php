<?php

namespace App\Http\Requests\Api\Admin\Request\Finance\WithdrawalRequest;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\Request\Finance\WithdrawalRequest
 */
class IndexRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'id',
        'created_at',
        'waiting',
        'amount',
        'request_status'
    ];

    /**
     * @return array
    */
    public function rules() : array
    {
        return [
            'request_id'               => 'string|nullable',
            'user_id'                  => 'integer|nullable',
            'date_from'                => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                  => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'username'                 => 'string|nullable',
            'languages_ids'            => 'array|nullable',
            'languages_ids.*'          => 'required|integer',
            'payout_method_id'         => 'integer|nullable',
            'amount'                   => 'integer|nullable',
            'user_balance_types_ids'   => 'array|nullable',
            'user_balance_types_ids.*' => 'required|integer',
            'request_statuses_ids'     => 'array|nullable',
            'request_statuses_ids.*'   => 'required|integer',
            'receipt_statuses_ids'     => 'array|nullable',
            'receipt_statuses_ids.*'   => 'required|integer',
            'admin'                    => 'string|nullable',
            'sort_by'                  => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'               => 'string|in:desc,asc|nullable',
            'paginated'                => 'boolean|nullable',
            'per_page'                 => 'integer|nullable',
            'page'                     => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'request_id.required'               => trans('validations/api/admin/request/finance/withdrawalRequest/index.request_id.required'),
            'request_id.string'                 => trans('validations/api/admin/request/finance/withdrawalRequest/index.request_id.string'),
            'user_id.required'                  => trans('validations/api/admin/request/finance/withdrawalRequest/index.user_id.required'),
            'user_id.integer'                   => trans('validations/api/admin/request/finance/withdrawalRequest/index.user_id.integer'),
            'date_from.string'                  => trans('validations/api/admin/request/finance/withdrawalRequest/index.date_from.string'),
            'date_from.date_format'             => trans('validations/api/admin/request/finance/withdrawalRequest/index.date_from.date_format'),
            'date_to.string'                    => trans('validations/api/admin/request/finance/withdrawalRequest/index.date_to.string'),
            'date_to.date_format'               => trans('validations/api/admin/request/finance/withdrawalRequest/index.date_to.date_format'),
            'username.string'                   => trans('validations/api/admin/request/finance/withdrawalRequest/index.username.string'),
            'languages_ids.array'               => trans('validations/api/admin/request/finance/withdrawalRequest/index.languages_ids.*.array'),
            'languages_ids.*.required'          => trans('validations/api/admin/request/finance/withdrawalRequest/index.languages_ids.*.required'),
            'languages_ids.*.integer'           => trans('validations/api/admin/request/finance/withdrawalRequest/index.languages_ids.*.integer'),
            'payout_method_id.required'         => trans('validations/api/admin/request/finance/withdrawalRequest/index.payout_method_id.required'),
            'payout_method_id.integer'          => trans('validations/api/admin/request/finance/withdrawalRequest/index.payout_method_id.integer'),
            'amount.integer'                    => trans('validations/api/admin/request/finance/withdrawalRequest/index.amount.integer'),
            'user_balance_types_ids.array'      => trans('validations/api/admin/request/finance/withdrawalRequest/index.user_balance_types_ids.*.array'),
            'user_balance_types_ids.*.required' => trans('validations/api/admin/request/finance/withdrawalRequest/index.user_balance_types_ids.*.required'),
            'user_balance_types_ids.*.integer'  => trans('validations/api/admin/request/finance/withdrawalRequest/index.user_balance_types_ids.*.integer'),
            'request_statuses_ids.array'        => trans('validations/api/admin/request/finance/withdrawalRequest/index.request_statuses_ids.*.array'),
            'request_statuses_ids.*.required'   => trans('validations/api/admin/request/finance/withdrawalRequest/index.request_statuses_ids.*.required'),
            'request_statuses_ids.*.integer'    => trans('validations/api/admin/request/finance/withdrawalRequest/index.request_statuses_ids.*.integer'),
            'receipt_statuses_ids.array'        => trans('validations/api/admin/request/finance/withdrawalRequest/index.receipt_statuses_ids.*.array'),
            'receipt_statuses_ids.*.required'   => trans('validations/api/admin/request/finance/withdrawalRequest/index.receipt_statuses_ids.*.required'),
            'receipt_statuses_ids.*.integer'    => trans('validations/api/admin/request/finance/withdrawalRequest/index.receipt_statuses_ids.*.integer'),
            'admin.string'                      => trans('validations/api/admin/request/finance/withdrawalRequest/index.admin.string'),
            'sort_by.string'                    => trans('validations/api/admin/request/finance/withdrawalRequest/index.sort_by.string'),
            'sort_by.in'                        => trans('validations/api/admin/request/finance/withdrawalRequest/index.sort_by.in'),
            'sort_order.string'                 => trans('validations/api/admin/request/finance/withdrawalRequest/index.sort_order.string'),
            'sort_order.in'                     => trans('validations/api/admin/request/finance/withdrawalRequest/index.sort_order.in'),
            'paginated.boolean'                 => trans('validations/api/admin/request/finance/withdrawalRequest/index.paginated.boolean'),
            'per_page.integer'                  => trans('validations/api/admin/request/finance/withdrawalRequest/index.per_page.integer'),
            'page.integer'                      => trans('validations/api/admin/request/finance/withdrawalRequest/index.page.integer')
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
            'user_id'                => isset($params['user_id']) ? (int) $params['user_id'] : null,
            'languages_ids'          => isset($params['languages_ids']) ? explodeUrlIds($params['languages_ids']) : null,
            'payout_method_id'       => isset($params['payout_method_id']) ? (int) $params['payout_method_id'] : null,
            'amount'                 => isset($params['amount']) ? (int) $params['amount'] : null,
            'user_balance_types_ids' => isset($params['user_balance_types_ids']) ? explodeUrlIds($params['user_balance_types_ids']) : null,
            'request_statuses_ids'   => isset($params['request_statuses_ids']) ? explodeUrlIds($params['request_statuses_ids']) : null,
            'receipt_statuses_ids'   => isset($params['receipt_statuses_ids']) ? explodeUrlIds($params['receipt_statuses_ids']) : null,
            'paginated'              => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'per_page'               => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'                   => isset($params['page']) ? (int) $params['page'] : null
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