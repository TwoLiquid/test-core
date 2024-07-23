<?php

namespace App\Http\Requests\Api\General\Setting\Account;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ChangeEmailRequest
 *
 * @package App\Http\Requests\Api\General\Setting\Account
 */
class ChangeEmailRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'email'    => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix',
            'password' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'email.required'    => trans('validations/api/general/setting/account/changeEmail.email.required'),
            'email.regex'       => trans('validations/api/general/setting/account/changeEmail.email.regex'),
            'email.unique'      => trans('validations/api/general/setting/account/changeEmail.email.unique'),
            'password.required' => trans('validations/api/general/setting/account/changeEmail.password.required'),
            'password.string'   => trans('validations/api/general/setting/account/changeEmail.password.string'),
        ];
    }
}
