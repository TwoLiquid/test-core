<?php

namespace App\Http\Requests\Api\Guest\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ResetPasswordRequest
 *
 * @package App\Http\Requests\Api\Guest\Auth
 */
class ResetPasswordRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'token'            => 'required|string',
            'email'            => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix',
            'password'         => 'required|string',
            'password_confirm' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'token.required'            => trans('validations/api/guest/auth/resetPassword.token.required'),
            'token.string'              => trans('validations/api/guest/auth/resetPassword.token.string'),
            'email.required'            => trans('validations/api/guest/auth/resetPassword.email.required'),
            'email.regex'               => trans('validations/api/guest/auth/resetPassword.email.regex'),
            'password.required'         => trans('validations/api/guest/auth/resetPassword.password.required'),
            'password.string'           => trans('validations/api/guest/auth/resetPassword.password.string'),
            'password_confirm.required' => trans('validations/api/guest/auth/resetPassword.password_confirm.required'),
            'password_confirm.string'   => trans('validations/api/guest/auth/resetPassword.password_confirm.string'),
        ];
    }
}
