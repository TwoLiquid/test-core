<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Activity;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdatePositionsRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Activity
 */
class UpdatePositionsRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'activities_items'            => 'required|array',
            'activities_items.*.id'       => 'required|integer|exists:activities,id',
            'activities_items.*.position' => 'required|integer'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'activities_items.required'            => trans('validations/api/admin/csau/category/activity/updatePositions.activities_items.required'),
            'activities_items.array'               => trans('validations/api/admin/csau/category/activity/updatePositions.activities_items.array'),
            'activities_items.*.id.required'       => trans('validations/api/admin/csau/category/activity/updatePositions.activities_items.*.id.required'),
            'activities_items.*.id.integer'        => trans('validations/api/admin/csau/category/activity/updatePositions.activities_items.*.id.integer'),
            'activities_items.*.id.exists'         => trans('validations/api/admin/csau/category/activity/updatePositions.activities_items.*.id.exists'),
            'activities_items.*.position.required' => trans('validations/api/admin/csau/category/activity/updatePositions.activities_items.*.position.required'),
            'activities_items.*.position.integer'  => trans('validations/api/admin/csau/category/activity/updatePositions.activities_items.*.position.integer')
        ];
    }
}
