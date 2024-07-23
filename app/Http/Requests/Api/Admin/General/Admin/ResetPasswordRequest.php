<?php

namespace App\Http\Requests\Api\Admin\General\Admin;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ResetPasswordRequest
 *
 * @package App\Http\Requests\Api\Admin\General\Admin
 */
class ResetPasswordRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'password' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'password.required' => trans('validations/api/admin/general/admin/resetPassword.password.required'),
            'password.string'   => trans('validations/api/admin/general/admin/resetPassword.password.string')
        ];
    }
}
