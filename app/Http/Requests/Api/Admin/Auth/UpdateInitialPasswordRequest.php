<?php

namespace App\Http\Requests\Api\Admin\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateInitialPasswordRequest
 *
 * @package App\Http\Requests\Api\Admin\Auth
 */
class UpdateInitialPasswordRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'email'                => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix|exists:admins,email',
            'password'             => 'required|string',
            'new_password'         => 'required|string',
            'new_password_confirm' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'email.required'                => trans('validations/api/admin/auth/updateInitialPassword.email.required'),
            'email.regex'                   => trans('validations/api/admin/auth/updateInitialPassword.email.regex'),
            'email.exists'                  => trans('validations/api/admin/auth/updateInitialPassword.email.exists'),
            'password.required'             => trans('validations/api/admin/auth/updateInitialPassword.password.required'),
            'password.string'               => trans('validations/api/admin/auth/updateInitialPassword.password.string'),
            'new_password.required'         => trans('validations/api/admin/auth/updateInitialPassword.new_password.required'),
            'new_password.string'           => trans('validations/api/admin/auth/updateInitialPassword.new_password.string'),
            'new_password_confirm.required' => trans('validations/api/admin/auth/updateInitialPassword.new_password_confirm.required'),
            'new_password_confirm.string'   => trans('validations/api/admin/auth/updateInitialPassword.new_password_confirm.string')
        ];
    }
}
