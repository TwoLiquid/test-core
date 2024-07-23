<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Activity;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class AttachActivityTagsRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Activity
 */
class AttachActivityTagsRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'activities_ids'      => 'required|array',
            'activities_ids.*'    => 'required|integer|exists:activities,id',
            'activity_tags_ids'   => 'required|array',
            'activity_tags_ids.*' => 'required|integer|exists:activity_tags,id'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'activities_ids.required'      => trans('validations/api/admin/csau/category/activity/attachActivityTags.activities_ids.required'),
            'activities_ids.array'         => trans('validations/api/admin/csau/category/activity/attachActivityTags.activities_ids.array'),
            'activities_ids.*.required'    => trans('validations/api/admin/csau/category/activity/attachActivityTags.activities_ids.*.required'),
            'activities_ids.*.integer'     => trans('validations/api/admin/csau/category/activity/attachActivityTags.activities_ids.*.integer'),
            'activities_ids.*.exists'      => trans('validations/api/admin/csau/category/activity/attachActivityTags.activities_ids.*.exists'),
            'activity_tags_ids.required'   => trans('validations/api/admin/csau/category/activity/attachActivityTags.activity_tags_ids.required'),
            'activity_tags_ids.array'      => trans('validations/api/admin/csau/category/activity/attachActivityTags.activity_tags_ids.array'),
            'activity_tags_ids.*.required' => trans('validations/api/admin/csau/category/activity/attachActivityTags.activity_tags_ids.*.required'),
            'activity_tags_ids.*.integer'  => trans('validations/api/admin/csau/category/activity/attachActivityTags.activity_tags_ids.*.integer'),
            'activity_tags_ids.*.exists'   => trans('validations/api/admin/csau/category/activity/attachActivityTags.activity_tags_ids.*.exists')
        ];
    }
}
