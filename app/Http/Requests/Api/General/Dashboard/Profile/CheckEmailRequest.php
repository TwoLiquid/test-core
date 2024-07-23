<?php

namespace App\Http\Requests\Api\General\Dashboard\Profile;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class CheckEmailRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Profile
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
            'email.required' => trans('validations/api/general/dashboard/profile/checkEmail.email.required'),
            'email.regex'    => trans('validations/api/general/dashboard/profile/checkEmail.email.regex')
        ];
    }
}
