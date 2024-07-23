<?php

namespace App\Http\Requests\Api\Admin\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class EnableTwoFactorRequest
 *
 * @package App\Http\Requests\Api\Admin\Auth
 */
class EnableTwoFactorRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'secret_key' => 'required|string',
            'email'      => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix|exists:admins,email',
            'password'   => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'secret_key.required' => trans('validations/api/admin/auth/enableTwoFactor.secret_key.required'),
            'secret_key.string'   => trans('validations/api/admin/auth/enableTwoFactor.secret_key.string'),
            'email.required'      => trans('validations/api/admin/auth/enableTwoFactor.email.required'),
            'email.regex'         => trans('validations/api/admin/auth/enableTwoFactor.email.regex'),
            'email.exists'        => trans('validations/api/admin/auth/enableTwoFactor.email.exists'),
            'password.required'   => trans('validations/api/admin/auth/enableTwoFactor.password.required'),
            'password.string'     => trans('validations/api/admin/auth/enableTwoFactor.password.string')
        ];
    }
}
