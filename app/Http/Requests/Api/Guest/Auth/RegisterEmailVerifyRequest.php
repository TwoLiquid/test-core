<?php

namespace App\Http\Requests\Api\Guest\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class RegisterEmailVerifyRequest
 *
 * @package App\Http\Requests\Api\Guest\Auth
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
            'email.required' => trans('validations/api/guest/auth/registerEmailVerify.email.required'),
            'email.regex'    => trans('validations/api/guest/auth/registerEmailVerify.email.regex'),
            'token.required' => trans('validations/api/guest/auth/registerEmailVerify.token.required'),
            'token.string'   => trans('validations/api/guest/auth/registerEmailVerify.token.string')
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
