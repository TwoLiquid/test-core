<?php

namespace App\Http\Requests\Api\Admin\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ExecuteTwoFactorRequest
 *
 * @package App\Http\Requests\Api\Admin\Auth
 */
class ExecuteTwoFactorRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'otp_password' => 'required|string',
            'email'        => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix|exists:admins,email',
            'password'     => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'otp_password.required' => trans('validations/api/admin/auth/executeTwoFactor.otp_password.required'),
            'otp_password.string'   => trans('validations/api/admin/auth/executeTwoFactor.otp_password.string'),
            'email.required'        => trans('validations/api/admin/auth/executeTwoFactor.email.required'),
            'email.regex'           => trans('validations/api/admin/auth/executeTwoFactor.email.regex'),
            'email.exists'          => trans('validations/api/admin/auth/executeTwoFactor.email.exists'),
            'password.required'     => trans('validations/api/admin/auth/executeTwoFactor.password.required'),
            'password.string'       => trans('validations/api/admin/auth/executeTwoFactor.password.string')
        ];
    }
}
