<?php

namespace App\Http\Requests\Api\Admin\Csau\Platform;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class AttachActivitiesRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Platform
 */
class AttachActivitiesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'activities_ids'   => 'required|array',
            'activities_ids.*' => 'required|integer|exists:activities,id'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'activities_ids.required'   => trans('validations/api/admin/csau/platform/attachActivities.activities_ids.required'),
            'activities_ids.array'      => trans('validations/api/admin/csau/platform/attachActivities.activities_ids.array'),
            'activities_ids.*.required' => trans('validations/api/admin/csau/platform/attachActivities.activities_ids.*.required'),
            'activities_ids.*.integer'  => trans('validations/api/admin/csau/platform/attachActivities.activities_ids.*.integer'),
            'activities_ids.*.exists'   => trans('validations/api/admin/csau/platform/attachActivities.activities_ids.*.exists')
        ];
    }
}
