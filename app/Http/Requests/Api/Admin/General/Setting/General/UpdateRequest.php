<?php

namespace App\Http\Requests\Api\Admin\General\Setting\General;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\General\Setting\General
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
            'settings.*.fields.*.value' => 'nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'settings.required'                 => trans('validations/api/admin/general/setting/general/update.settings.required'),
            'settings.array'                    => trans('validations/api/admin/general/setting/general/update.settings.array'),
            'settings.*.code.required'          => trans('validations/api/admin/general/setting/general/update.settings.*.required'),
            'settings.*.code.string'            => trans('validations/api/admin/general/setting/general/update.settings.*.string'),
            'settings.*.fields.required'        => trans('validations/api/admin/general/setting/general/update.settings.*.fields.required'),
            'settings.*.fields.array'           => trans('validations/api/admin/general/setting/general/update.settings.*.fields.array'),
            'settings.*.fields.*.code.required' => trans('validations/api/admin/general/setting/general/update.settings.*.fields.*.code.required'),
            'settings.*.fields.*.code.string'   => trans('validations/api/admin/general/setting/general/update.settings.*.fields.*.code.string')
        ];
    }
}
