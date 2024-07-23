<?php

namespace App\Http\Requests\Api\Admin\User\User;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class DestroyRequest
 *
 * @package App\Http\Requests\Api\Admin\User\User
 */
class DestroyRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'password' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'password.required' => trans('validations/api/admin/user/user/destroy.password.required'),
            'password.string'   => trans('validations/api/admin/user/user/destroy.password.string')
        ];
    }
}
