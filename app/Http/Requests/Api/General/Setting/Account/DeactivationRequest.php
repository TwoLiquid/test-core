<?php

namespace App\Http\Requests\Api\General\Setting\Account;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class DeactivationRequest
 *
 * @package App\Http\Requests\Api\General\Setting\Account
 */
class DeactivationRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'otp_password' => 'required|string',
            'reason'       => 'string|nullable',
            'password'     => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'otp_password.required' => trans('validations/api/general/setting/account/deactivation.otp_password.required'),
            'otp_password.string'   => trans('validations/api/general/setting/account/deactivation.otp_password.string'),
            'reason.string'         => trans('validations/api/general/setting/account/deactivation.reason.string'),
            'password.required'     => trans('validations/api/general/setting/account/deactivation.password.required'),
            'password.string'       => trans('validations/api/general/setting/account/deactivation.password.string')
        ];
    }
}
