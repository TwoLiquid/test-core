<?php

namespace App\Http\Requests\Api\General\Dashboard\Finance\Buyer\AddFunds\Receipt;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Finance\Buyer\AddFunds\Receipt
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'receipt_id'                       => 'integer|nullable',
            'date_from'                        => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                          => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'amount'                           => 'integer|nullable',
            'payment_fee'                      => 'integer|nullable',
            'total'                            => 'integer|nullable',
            'payment_methods_ids'              => 'array|nullable',
            'payment_methods_ids.*'            => 'required|integer|exists:payment_methods,id',
            'add_funds_receipt_statuses_ids'   => 'array|nullable',
            'add_funds_receipt_statuses_ids.*' => 'required|integer|between:1,4',
            'page'                             => 'integer|nullable',
            'per_page'                         => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'receipt_id.integer'                        => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.receipt_id.integer'),
            'date_from.date_format'                     => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.date_from.date_format'),
            'date_to.date_format'                       => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.date_to.date_format'),
            'amount.integer'                            => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.amount.integer'),
            'payment_fee.integer'                       => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.payment_fee.integer'),
            'total.integer'                             => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.total.integer'),
            'payment_methods_ids.array'                 => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.payment_methods_ids.array'),
            'payment_methods_ids.*.required'            => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.payment_methods_ids.*.required'),
            'payment_methods_ids.*.integer'             => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.payment_methods_ids.*.integer'),
            'payment_methods_ids.*.exists'              => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.payment_methods_ids.*.exists'),
            'add_funds_receipt_statuses_ids.array'      => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.add_funds_receipt_statuses_ids.array'),
            'add_funds_receipt_statuses_ids.*.required' => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.add_funds_receipt_statuses_ids.*.required'),
            'add_funds_receipt_statuses_ids.*.integer'  => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.add_funds_receipt_statuses_ids.*.integer'),
            'add_funds_receipt_statuses_ids.*.between'  => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.add_funds_receipt_statuses_ids.*.between'),
            'page.integer'                              => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.page.integer'),
            'per_page.integer'                          => trans('validations/api/general/dashboard/finance/buyer/addFunds/receipt/index.per_page.integer')
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
            'receipt_id'                     => isset($params['receipt_id']) ? (int) $params['receipt_id'] : null,
            'amount'                         => isset($params['amount']) ? (int) $params['amount'] : null,
            'payment_fee'                    => isset($params['payment_fee']) ? (int) $params['payment_fee'] : null,
            'total'                          => isset($params['total']) ? (int) $params['total'] : null,
            'payment_methods_ids'            => isset($params['payment_methods_ids']) ? explodeUrlIds($params['payment_methods_ids']) : null,
            'add_funds_receipt_statuses_ids' => isset($params['add_funds_receipt_statuses_ids']) ? explodeUrlIds($params['add_funds_receipt_statuses_ids']) : null,
            'page'                           => isset($params['page']) ? (int) $params['page'] : null,
            'per_page'                       => isset($params['per_page']) ? (int) $params['per_page'] : null
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
