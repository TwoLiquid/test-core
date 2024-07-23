<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class RegisterEmailVerifyRequest
 *
 * @package App\Http\Requests\Auth
 */
class RegisterEmailVerifyRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix',
            'token' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'email.required' => trans('validations/auth/registerEmailVerify.email.required'),
            'email.regex'    => trans('validations/auth/registerEmailVerify.email.regex'),
            'token.required' => trans('validations/auth/registerEmailVerify.token.required'),
            'token.string'   => trans('validations/auth/registerEmailVerify.token.string')
        ];
    }

    /**
     * @param null $keys
     *
     * @return array
     */
    public function all($keys = null) : array
    {
        return array_merge(
            parent::all(),
            $this->route()->parameters()
        );
    }
}
