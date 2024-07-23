<?php

namespace App\Http\Requests\Api\General\User;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\General\User
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'email'    => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix|unique:users,email',
            'password' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'email.required'    => trans('validations/api/general/user/store.email.required'),
            'email.regex'      => trans('validations/api/general/user/store.email.regex'),
            'email.unique'      => trans('validations/api/general/user/store.email.unique'),
            'password.required' => trans('validations/api/general/user/store.password.required'),
            'password.string'   => trans('validations/api/general/user/store.password.string')
        ];
    }
}
