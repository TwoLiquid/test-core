<?php

namespace App\Http\Requests\Api\Admin\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class LoginRequest
 *
 * @package App\Http\Requests\Api\Admin\Auth
 */
class LoginRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'email'    => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix|exists:admins,email',
            'password' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'email.required'    => trans('validations/api/admin/auth/login.email.required'),
            'email.regex'       => trans('validations/api/admin/auth/login.email.regex'),
            'email.exists'      => trans('validations/api/admin/auth/login.email.exists'),
            'password.required' => trans('validations/api/admin/auth/login.password.required'),
            'password.string'   => trans('validations/api/admin/auth/login.password.string')
        ];
    }
}
