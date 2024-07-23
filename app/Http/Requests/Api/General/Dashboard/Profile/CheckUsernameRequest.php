<?php

namespace App\Http\Requests\Api\General\Dashboard\Profile;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class CheckUsernameRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Profile
 */
class CheckUsernameRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'username' => 'required|string|unique:users,username'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'username.required' => trans('validations/api/general/dashboard/profile/checkUsername.username.required'),
            'username.string'   => trans('validations/api/general/dashboard/profile/checkUsername.username.string'),
            'username.unique'   => trans('validations/api/general/dashboard/profile/checkUsername.username.unique')
        ];
    }
}
