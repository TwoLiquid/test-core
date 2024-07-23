<?php

namespace App\Http\Requests\Api\Admin\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class PasswordSetupRequest
 *
 * @package App\Http\Requests\Api\Admin\Auth
 */
class PasswordSetupRequest extends BaseRequest
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
            'token.required'            => trans('validations/api/admin/auth/passwordSetup.token.required'),
            'token.string'              => trans('validations/api/admin/auth/passwordSetup.token.string'),
            'email.required'            => trans('validations/api/admin/auth/passwordSetup.email.required'),
            'email.regex'               => trans('validations/api/admin/auth/passwordSetup.email.regex'),
            'password.required'         => trans('validations/api/admin/auth/passwordSetup.password.required'),
            'password.string'           => trans('validations/api/admin/auth/passwordSetup.password.string'),
            'password_confirm.required' => trans('validations/api/admin/auth/passwordSetup.password_confirm.required'),
            'password_confirm.string'   => trans('validations/api/admin/auth/passwordSetup.password_confirm.string'),
        ];
    }
}
