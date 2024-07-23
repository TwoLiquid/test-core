<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Unit;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdatePositionsRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Unit
 */
class UpdatePositionsRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'activity_id'            => 'required|integer|exists:activities,id',
            'units_items'            => 'required|array',
            'units_items.*.id'       => 'required|integer|exists:units,id',
            'units_items.*.position' => 'required|integer'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'activity_id.required'            => trans('validations/api/admin/csau/category/unit/updatePositions.activity_id.required'),
            'activity_id.integer'             => trans('validations/api/admin/csau/category/unit/updatePositions.activity_id.integer'),
            'activity_id.exists'              => trans('validations/api/admin/csau/category/unit/updatePositions.activity_id.exists'),
            'units_items.required'            => trans('validations/api/admin/csau/category/unit/updatePositions.units_items.required'),
            'units_items.array'               => trans('validations/api/admin/csau/category/unit/updatePositions.units_items.array'),
            'units_items.*.id.required'       => trans('validations/api/admin/csau/category/unit/updatePositions.units_items.*.id.required'),
            'units_items.*.id.integer'        => trans('validations/api/admin/csau/category/unit/updatePositions.units_items.*.id.integer'),
            'units_items.*.id.exists'         => trans('validations/api/admin/csau/category/unit/updatePositions.units_items.*.id.exists'),
            'units_items.*.position.required' => trans('validations/api/admin/csau/category/unit/updatePositions.units_items.*.position.required'),
            'units_items.*.position.integer'  => trans('validations/api/admin/csau/category/unit/updatePositions.units_items.*.position.integer')
        ];
    }
}
