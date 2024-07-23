<?php

namespace App\Http\Requests\Api\General\Tip;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class CancelPaymentRequest
 *
 * @package App\Http\Requests\Api\General\Tip
 */
class CancelPaymentRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'hash' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'hash.required' => trans('validations/api/general/tip/cancelPayment.hash.required'),
            'hash.string'   => trans('validations/api/general/tip/cancelPayment.hash.string')
        ];
    }
}
