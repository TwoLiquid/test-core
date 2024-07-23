<?php

namespace App\Http\Requests\Api\General\Dashboard\Finance\Seller\Withdrawal\Receipt;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Finance\Seller\Withdrawal\Receipt
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'request_id'             => 'string|nullable',
            'receipt_id'             => 'integer|nullable',
            'request_date_from'      => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'request_date_to'        => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'receipt_date_from'      => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'receipt_date_to'        => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'amount'                 => 'integer|nullable',
            'payment_methods_ids'    => 'array|nullable',
            'payment_methods_ids.*'  => 'required|integer|exists:payment_methods,id',
            'request_statuses_ids'   => 'array|nullable',
            'request_statuses_ids.*' => 'required|integer|between:1,4',
            'receipt_statuses_ids'   => 'array|nullable',
            'receipt_statuses_ids.*' => 'required|integer|between:1,3',
            'page'                   => 'integer|nullable',
            'per_page'               => 'integer|nullable',
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'request_id.string'               => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.request_id.string'),
            'receipt_id.integer'              => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.receipt_id.integer'),
            'request_date_from.date_format'   => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.request_date_from.date_format'),
            'request_date_to.date_format'     => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.request_date_to.date_format'),
            'receipt_date_from.date_format'   => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.receipt_date_from.date_format'),
            'receipt_date_to.date_format'     => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.receipt_date_to.date_format'),
            'amount.integer'                  => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.amount.integer'),
            'payment_methods_ids.array'       => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.payment_methods_ids.array'),
            'payment_methods_ids.*.required'  => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.payment_methods_ids.*.required'),
            'payment_methods_ids.*.integer'   => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.payment_methods_ids.*.integer'),
            'payment_methods_ids.*.exists'    => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.payment_methods_ids.*.exists'),
            'request_statuses_ids.array'      => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.request_statuses_ids.array'),
            'request_statuses_ids.*.required' => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.request_statuses_ids.*.required'),
            'request_statuses_ids.*.integer'  => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.request_statuses_ids.*.integer'),
            'request_statuses_ids.*.between'  => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.request_statuses_ids.*.between'),
            'receipt_statuses_ids.array'      => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.receipt_statuses_ids.array'),
            'receipt_statuses_ids.*.required' => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.receipt_statuses_ids.*.required'),
            'receipt_statuses_ids.*.integer'  => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.receipt_statuses_ids.*.integer'),
            'receipt_statuses_ids.*.between'  => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.receipt_statuses_ids.*.between'),
            'page.integer'                    => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.page.integer'),
            'per_page.integer'                => trans('validations/api/general/dashboard/finance/seller/withdrawal/receipt/index.per_page.integer')
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
            'amount'               => isset($params['amount']) ? (int) $params['amount'] : null,
            'payment_methods_ids'  => isset($params['payment_methods_ids']) ? explodeUrlIds($params['payment_methods_ids']) : null,
            'request_statuses_ids' => isset($params['request_statuses_ids']) ? explodeUrlIds($params['request_statuses_ids']) : null,
            'receipt_statuses_ids' => isset($params['receipt_statuses_ids']) ? explodeUrlIds($params['receipt_statuses_ids']) : null,
            'page'                 => isset($params['page']) ? (int) $params['page'] : null,
            'per_page'             => isset($params['per_page']) ? (int) $params['per_page'] : null
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
