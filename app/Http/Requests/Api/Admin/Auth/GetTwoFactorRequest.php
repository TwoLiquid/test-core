<?php

namespace App\Http\Requests\Api\Admin\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetTwoFactorRequest
 *
 * @package App\Http\Requests\Api\Admin\Auth
 */
class GetTwoFactorRequest extends BaseRequest
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
            'email.required'    => trans('validations/api/admin/auth/getTwoFactor.email.required'),
            'email.regex'       => trans('validations/api/admin/auth/getTwoFactor.email.regex'),
            'email.exists'      => trans('validations/api/admin/auth/getTwoFactor.email.exists'),
            'password.required' => trans('validations/api/admin/auth/getTwoFactor.password.required'),
            'password.string'   => trans('validations/api/admin/auth/getTwoFactor.password.string')
        ];
    }
}
