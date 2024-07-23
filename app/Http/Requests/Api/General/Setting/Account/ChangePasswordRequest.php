<?php

namespace App\Http\Requests\Api\General\Setting\Account;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ChangePasswordRequest
 *
 * @package App\Http\Requests\Api\General\Setting\Account
 */
class ChangePasswordRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'password'     => 'required|string',
            'new_password' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'password.required'     => trans('validations/api/general/setting/account/changePassword.password.required'),
            'password.string'       => trans('validations/api/general/setting/account/changePassword.password.string'),
            'new_password.required' => trans('validations/api/general/setting/account/changePassword.new_password.required'),
            'new_password.string'   => trans('validations/api/general/setting/account/changePassword.new_password.string')
        ];
    }
}
