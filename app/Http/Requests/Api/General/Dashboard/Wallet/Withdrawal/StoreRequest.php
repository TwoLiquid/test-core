<?php

namespace App\Http\Requests\Api\General\Dashboard\Wallet\Withdrawal;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Wallet\Withdrawal
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'method_id' => 'required|integer|exists:payment_methods,id',
            'amount'    => 'required|integer'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'method_id.required' => trans('validations/api/general/dashboard/wallet/withdrawal/store.method_id.required'),
            'method_id.integer'  => trans('validations/api/general/dashboard/wallet/withdrawal/store.method_id.integer'),
            'method_id.exists'   => trans('validations/api/general/dashboard/wallet/withdrawal/store.method_id.exists'),
            'amount.required'    => trans('validations/api/general/dashboard/wallet/withdrawal/store.amount.required'),
            'amount.integer'     => trans('validations/api/general/dashboard/wallet/withdrawal/store.amount.integer')
        ];
    }
}
