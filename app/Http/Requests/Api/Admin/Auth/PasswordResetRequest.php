<?php

namespace App\Http\Requests\Api\Admin\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class PasswordResetRequest
 *
 * @package App\Http\Requests\Api\Admin\Auth
 */
class PasswordResetRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'email.required' => trans('validations/api/admin/auth/passwordReset.email.required'),
            'email.regex'    => trans('validations/api/admin/auth/passwordReset.email.regex')
        ];
    }
}
