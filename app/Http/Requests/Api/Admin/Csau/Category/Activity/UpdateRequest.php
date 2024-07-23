<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Activity;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Activity
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => [
                'array',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'visible'                     => 'boolean|nullable',
            'activity_images'             => 'array|nullable',
            'activity_images.*.content'   => 'required|string',
            'activity_images.*.extension' => 'required|string',
            'activity_images.*.mime'      => 'required|string',
            'activity_images.*.type'      => 'required|in:poster,avatar,background'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.array'                           => trans('validations/api/admin/csau/category/activity/update.name.array'),
            'visible.boolean'                      => trans('validations/api/admin/csau/category/activity/update.visible.boolean'),
            'activity_images.required'             => trans('validations/api/admin/csau/category/activity/update.activity_images.required'),
            'activity_images.array'                => trans('validations/api/admin/csau/category/activity/update.activity_images.array'),
            'activity_images.*.content.required'   => trans('validations/api/admin/csau/category/activity/update.activity_images.*.content.required'),
            'activity_images.*.content.string'     => trans('validations/api/admin/csau/category/activity/update.activity_images.*.content.string'),
            'activity_images.*.mime.required'      => trans('validations/api/admin/csau/category/activity/update.activity_images.*.mime.required'),
            'activity_images.*.mime.string'        => trans('validations/api/admin/csau/category/activity/update.activity_images.*.mime.string'),
            'activity_images.*.extension.required' => trans('validations/api/admin/csau/category/activity/update.activity_images.*.extension.required'),
            'activity_images.*.extension.string'   => trans('validations/api/admin/csau/category/activity/update.activity_images.*.extension.string'),
            'activity_images.*.type.required'      => trans('validations/api/admin/csau/category/activity/update.activity_images.*.type.required'),
            'activity_images.*.type.in'            => trans('validations/api/admin/csau/category/activity/update.activity_images.*.type.in')
        ];
    }
}
