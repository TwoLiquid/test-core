<?php

namespace App\Http\Requests\Api\Admin\General\Setting\Request;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 * 
 * @package App\Http\Requests\Api\Admin\General\Setting\Request
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'settings'                  => 'required|array',
            'settings.*.code'           => 'required|string',
            'settings.*.fields'         => 'required|array',
            'settings.*.fields.*.code'  => 'required|string',
            'settings.*.fields.*.value' => 'required'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'settings.required'                  => trans('validations/api/admin/general/setting/request/update.settings.required'),
            'settings.array'                     => trans('validations/api/admin/general/setting/request/update.settings.array'),
            'settings.*.code.required'           => trans('validations/api/admin/general/setting/request/update.settings.*.required'),
            'settings.*.code.string'             => trans('validations/api/admin/general/setting/request/update.settings.*.string'),
            'settings.*.fields.required'         => trans('validations/api/admin/general/setting/request/update.settings.*.fields.required'),
            'settings.*.fields.array'            => trans('validations/api/admin/general/setting/request/update.settings.*.fields.array'),
            'settings.*.fields.*.code.required'  => trans('validations/api/admin/general/setting/request/update.settings.*.fields.*.code.required'),
            'settings.*.fields.*.code.string'    => trans('validations/api/admin/general/setting/request/update.settings.*.fields.*.code.string'),
            'settings.*.fields.*.value.required' => trans('validations/api/admin/general/setting/request/update.settings.*.fields.*.value.required')
        ];
    }
}
