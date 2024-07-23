<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Activity;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class AttachUnitsRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Activity
 */
class AttachUnitsRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'units_ids'   => 'required|array',
            'units_ids.*' => 'required|integer|exists:units,id',
            'visible'     => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'units_ids.required'   => trans('validations/api/admin/csau/category/activity/attachUnits.units_ids.required'),
            'units_ids.array'      => trans('validations/api/admin/csau/category/activity/attachUnits.units_ids.array'),
            'units_ids.*.required' => trans('validations/api/admin/csau/category/activity/attachUnits.units_ids.*.required'),
            'units_ids.*.integer'  => trans('validations/api/admin/csau/category/activity/attachUnits.units_ids.*.integer'),
            'units_ids.*.exists'   => trans('validations/api/admin/csau/category/activity/attachUnits.units_ids.*.exists'),
            'visible.required'     => trans('validations/api/admin/csau/category/activity/attachUnits.visible.required'),
            'visible.boolean'      => trans('validations/api/admin/csau/category/activity/attachUnits.visible.boolean')
        ];
    }
}
