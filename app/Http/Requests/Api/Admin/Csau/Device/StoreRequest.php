<?php

namespace App\Http\Requests\Api\Admin\Csau\Device;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Device
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'           => 'required|string',
            'visible'        => 'required|boolean',
            'icon'           => 'array|nullable',
            'icon.content'   => 'string|sometimes',
            'icon.extension' => 'string|sometimes',
            'icon.mime'      => 'string|sometimes'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.required'         => trans('validations/api/admin/csau/device/store.name.required'),
            'name.string'           => trans('validations/api/admin/csau/device/store.name.string'),
            'visible.required'      => trans('validations/api/admin/csau/device/store.visible.required'),
            'visible.boolean'       => trans('validations/api/admin/csau/device/store.visible.boolean'),
            'icon.array'            => trans('validations/api/admin/csau/device/store.icon.array'),
            'icon.content.string'   => trans('validations/api/admin/csau/device/store.icon.content.string'),
            'icon.extension.string' => trans('validations/api/admin/csau/device/store.icon.extension.string'),
            'icon.mime.string'      => trans('validations/api/admin/csau/device/store.icon.mime.string')
        ];
    }
}
