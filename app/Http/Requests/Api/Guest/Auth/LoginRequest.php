<?php

namespace App\Http\Requests\Api\Guest\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class LoginRequest
 *
 * @package App\Http\Requests\Api\Guest\Auth
 */
class LoginRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'login'    => 'required|string',
            'password' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'login.required'    => trans('validations/api/guest/auth/login.login.required'),
            'login.string'      => trans('validations/api/guest/auth/login.login.string'),
            'password.required' => trans('validations/api/guest/auth/login.password.required'),
            'password.string'   => trans('validations/api/guest/auth/login.password.string')
        ];
    }
}
