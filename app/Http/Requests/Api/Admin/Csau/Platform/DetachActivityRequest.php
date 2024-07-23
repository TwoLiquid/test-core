<?php

namespace App\Http\Requests\Api\Admin\Csau\Platform;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class DetachActivityRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Platform
 */
class DetachActivityRequest extends BaseRequest
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
            'password.required' => trans('validations/api/admin/csau/platform/detachActivity.password.required'),
            'password.string'   => trans('validations/api/admin/csau/platform/detachActivity.password.string')
        ];
    }
}
