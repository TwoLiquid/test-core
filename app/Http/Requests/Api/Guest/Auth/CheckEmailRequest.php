<?php

namespace App\Http\Requests\Api\Guest\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class CheckEmailRequest
 *
 * @package App\Http\Requests\Api\Guest\Auth
 */
class CheckEmailRequest extends BaseRequest
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
            'email.required' => trans('validations/api/guest/auth/checkEmail.email.required'),
            'email.regex'    => trans('validations/api/guest/auth/checkEmail.email.regex')
        ];
    }
}
