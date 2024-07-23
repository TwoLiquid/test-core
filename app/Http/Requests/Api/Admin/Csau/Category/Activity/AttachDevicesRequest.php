<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Activity;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class AttachDevicesRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Activity
 */
class AttachDevicesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'devices_ids'   => 'required|array',
            'devices_ids.*' => 'required|integer|exists:devices,id'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'devices_ids.required'   => trans('validations/api/admin/csau/category/activity/attachDevices.devices_ids.required'),
            'devices_ids.array'      => trans('validations/api/admin/csau/category/activity/attachDevices.devices_ids.array'),
            'devices_ids.*.required' => trans('validations/api/admin/csau/category/activity/attachDevices.devices_ids.*.required'),
            'devices_ids.*.integer'  => trans('validations/api/admin/csau/category/activity/attachDevices.devices_ids.*.integer'),
            'devices_ids.*.exists'   => trans('validations/api/admin/csau/category/activity/attachDevices.devices_ids.*.exists'),
        ];
    }
}
