<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Activity;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class AttachPlatformsRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Activity
 */
class AttachPlatformsRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'platforms_ids'   => 'required|array',
            'platforms_ids.*' => 'required|integer|exists:platforms,id'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'platforms_ids.required'   => trans('validations/api/admin/csau/category/activity/attachPlatforms.platforms_ids.required'),
            'platforms_ids.array'      => trans('validations/api/admin/csau/category/activity/attachPlatforms.platforms_ids.array'),
            'platforms_ids.*.required' => trans('validations/api/admin/csau/category/activity/attachPlatforms.platforms_ids.*.required'),
            'platforms_ids.*.integer'  => trans('validations/api/admin/csau/category/activity/attachPlatforms.platforms_ids.*.integer'),
            'platforms_ids.*.exists'   => trans('validations/api/admin/csau/category/activity/attachPlatforms.platforms_ids.*.exists'),
        ];
    }
}
