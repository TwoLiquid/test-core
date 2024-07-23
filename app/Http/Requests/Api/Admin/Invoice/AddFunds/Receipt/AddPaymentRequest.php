<?php

namespace App\Http\Requests\Api\Admin\Invoice\AddFunds\Receipt;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class AddPaymentRequest
 *
 * @package App\Http\Requests\Api\Admin\Invoice\AddFunds\Receipt
 */
class AddPaymentRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'payment_date'    => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'method_id'       => 'required|integer|exists:payment_methods,id',
            'external_id'     => 'string|nullable',
            'amount'          => 'required|numeric',
            'transaction_fee' => 'numeric|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'payment_date.required'    => trans('validations/api/admin/invoice/addFunds/receipt/addPayment.payment_date.required'),
            'payment_date.date_format' => trans('validations/api/admin/invoice/addFunds/receipt/addPayment.payment_date.date_format'),
            'method_id.required'       => trans('validations/api/admin/invoice/addFunds/receipt/addPayment.method_id.required'),
            'method_id.integer'        => trans('validations/api/admin/invoice/addFunds/receipt/addPayment.method_id.integer'),
            'method_id.exists'         => trans('validations/api/admin/invoice/addFunds/receipt/addPayment.method_id.exists'),
            'external_id.required'     => trans('validations/api/admin/invoice/addFunds/receipt/addPayment.external_id.required'),
            'external_id.string'       => trans('validations/api/admin/invoice/addFunds/receipt/addPayment.external_id.string'),
            'amount.required'          => trans('validations/api/admin/invoice/addFunds/receipt/addPayment.amount.required'),
            'amount.numeric'           => trans('validations/api/admin/invoice/addFunds/receipt/addPayment.amount.numeric'),
            'transaction_fee.numeric'  => trans('validations/api/admin/invoice/addFunds/receipt/addPayment.transaction_fee.numeric')
        ];
    }
}
