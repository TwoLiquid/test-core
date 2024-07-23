<?php

namespace App\Http\Requests\Api\General\Profile\Vybe;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ShowRequest
 *
 * @package App\Http\Requests\Api\General\Profile\Vybe
 */
class ShowRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'access_password' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'access_password.required' => trans('validations/api/general/profile/vybe/show.access_password.required'),
            'access_password.string'   => trans('validations/api/general/profile/vybe/show.access_password.string')
        ];
    }
}
