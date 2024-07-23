<?php

namespace App\Http\Requests\Api\General\User;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\User
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'email'    => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix|nullable|unique:users,email,' . $this->route('id'),
            'password' => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'email.regex'    => trans('validations/api/general/user/update.email.regex'),
            'email.unique'    => trans('validations/api/general/user/update.email.unique'),
            'password.string' => trans('validations/api/general/user/update.password.string')
        ];
    }
}
