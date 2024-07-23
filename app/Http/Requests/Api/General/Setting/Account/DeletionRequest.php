<?php

namespace App\Http\Requests\Api\General\Setting\Account;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class DeletionRequest
 *
 * @package App\Http\Requests\Api\General\Setting\Account
 */
class DeletionRequest extends BaseRequest
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
            'otp_password.required' => trans('validations/api/general/setting/account/deletion.otp_password.required'),
            'otp_password.string'   => trans('validations/api/general/setting/account/deletion.otp_password.string'),
            'reason.string'         => trans('validations/api/general/setting/account/deletion.reason.string'),
            'password.required'     => trans('validations/api/general/setting/account/deletion.password.required'),
            'password.string'       => trans('validations/api/general/setting/account/deletion.password.string')
        ];
    }
}
