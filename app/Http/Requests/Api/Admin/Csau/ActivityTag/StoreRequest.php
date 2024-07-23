<?php

namespace App\Http\Requests\Api\Admin\Csau\ActivityTag;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\ActivityTag
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => [
                'required',
                'array',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'category_id'            => 'required|integer|exists:categories,id',
            'visible_in_category'    => 'required|boolean',
            'subcategory_id'         => 'integer|exists:categories,id|nullable',
            'visible_in_subcategory' => 'required|boolean',
            'activities_ids'         => 'required|array',
            'activities_ids.*'       => 'required|integer|exists:activities,id'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.required'                   => trans('validations/api/admin/csau/activityTag/store.name.required'),
            'name.array'                      => trans('validations/api/admin/csau/activityTag/store.name.array'),
            'category_id.required'            => trans('validations/api/admin/csau/activityTag/store.category_id.required'),
            'category_id.integer'             => trans('validations/api/admin/csau/activityTag/store.category_id.integer'),
            'category_id.exists'              => trans('validations/api/admin/csau/activityTag/store.category_id.exists'),
            'visible_in_category.required'    => trans('validations/api/admin/csau/activityTag/store.visible_in_category.required'),
            'visible_in_category.boolean'     => trans('validations/api/admin/csau/activityTag/store.visible_in_category.boolean'),
            'subcategory_id.integer'          => trans('validations/api/admin/csau/activityTag/store.subcategory_id.integer'),
            'subcategory_id.exists'           => trans('validations/api/admin/csau/activityTag/store.subcategory_id.exists'),
            'visible_in_subcategory.required' => trans('validations/api/admin/csau/activityTag/store.visible_in_subcategory.required'),
            'visible_in_subcategory.boolean'  => trans('validations/api/admin/csau/activityTag/store.visible_in_subcategory.boolean'),
            'activities_ids.required'         => trans('validations/api/admin/csau/activityTag/store.activities_ids.required'),
            'activities_ids.array'            => trans('validations/api/admin/csau/activityTag/store.activities_ids.array'),
            'activities_ids.*.required'       => trans('validations/api/admin/csau/activityTag/store.activities_ids.*.required'),
            'activities_ids.*.integer'        => trans('validations/api/admin/csau/activityTag/store.activities_ids.*.integer'),
            'activities_ids.*.exists'         => trans('validations/api/admin/csau/activityTag/store.activities_ids.*.exists')
        ];
    }
}
