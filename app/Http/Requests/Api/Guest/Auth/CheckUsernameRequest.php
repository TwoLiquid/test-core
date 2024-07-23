<?php

namespace App\Http\Requests\Api\Guest\Auth;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Username\AlphanumericRule;
use App\Rules\Username\BeginFromLetterRule;
use App\Rules\Username\DoubleUnderscoreRule;

/**
 * Class RegisterRequest
 *
 * @package App\Http\Requests\Api\Guest\Auth
 */
class CheckUsernameRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:25',
                'unique:users,username',
                new AlphanumericRule(),
                new BeginFromLetterRule(),
                new DoubleUnderscoreRule()
            ]
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'username.required' => trans('validations/api/guest/auth/checkUsername.username.required'),
            'username.string'   => trans('validations/api/guest/auth/checkUsername.username.string'),
            'username.regex'    => trans('validations/api/guest/auth/checkUsername.username.regex'),
            'username.min'      => trans('validations/api/guest/auth/checkUsername.username.min'),
            'username.max'      => trans('validations/api/guest/auth/checkUsername.username.max'),
            'username.unique'   => trans('validations/api/guest/auth/checkUsername.username.unique')
        ];
    }
}
