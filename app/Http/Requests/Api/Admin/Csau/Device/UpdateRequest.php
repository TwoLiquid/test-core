<?php

namespace App\Http\Requests\Api\Admin\Csau\Device;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Device
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'           => 'string|nullable',
            'visible'        => 'boolean|nullable',
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
            'name.string'           => trans('validations/api/admin/csau/device/update.name.string'),
            'visible.boolean'       => trans('validations/api/admin/csau/device/update.visible.boolean'),
            'icon.array'            => trans('validations/api/admin/csau/device/update.icon.array'),
            'icon.content.string'   => trans('validations/api/admin/csau/device/update.icon.content.string'),
            'icon.extension.string' => trans('validations/api/admin/csau/device/update.icon.extension.string'),
            'icon.mime.string'      => trans('validations/api/admin/csau/device/update.icon.mime.string')
        ];
    }
}