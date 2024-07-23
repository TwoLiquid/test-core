<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Activity;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class DetachDeviceRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Activity
 */
class DetachDeviceRequest extends BaseRequest
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
            'password.required' => trans('validations/api/admin/csau/category/activity/detachDevice.password.required'),
            'password.string'   => trans('validations/api/admin/csau/category/activity/detachDevice.password.string')
        ];
    }
}
