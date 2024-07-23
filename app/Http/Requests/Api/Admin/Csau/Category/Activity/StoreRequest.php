<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Activity;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Activity
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
            'category_id'     => 'required|integer|exists:categories,id',
            'subcategory_id'  => 'integer|exists:categories,id|nullable',
            'devices_ids'     => 'array|nullable',
            'devices_ids.*'   => 'required|integer|exists:devices,id',
            'platforms_ids'   => 'required|array',
            'platforms_ids.*' => 'required|integer|exists:platforms,id',
            'visible'         => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.required'            => trans('validations/api/admin/csau/category/activity/store.name.required'),
            'name.array'               => trans('validations/api/admin/csau/category/activity/store.name.array'),
            'category_id.required'     => trans('validations/api/admin/csau/category/activity/store.category_id.required'),
            'category_id.integer'      => trans('validations/api/admin/csau/category/activity/store.category_id.integer'),
            'category_id.exists'       => trans('validations/api/admin/csau/category/activity/store.category_id.exists'),
            'subcategory_id.integer'   => trans('validations/api/admin/csau/category/activity/store.subcategory_id.integer'),
            'subcategory_id.exists'    => trans('validations/api/admin/csau/category/activity/store.subcategory_id.exists'),
            'devices_ids.array'        => trans('validations/api/admin/csau/category/activity/store.devices_ids.array'),
            'devices_ids.*.required'   => trans('validations/api/admin/csau/category/activity/store.devices_ids.*.required'),
            'devices_ids.*.integer'    => trans('validations/api/admin/csau/category/activity/store.devices_ids.*.integer'),
            'devices_ids.*.exists'     => trans('validations/api/admin/csau/category/activity/store.devices_ids.*.exists'),
            'platforms_ids.required'   => trans('validations/api/admin/csau/category/activity/store.platforms_ids.required'),
            'platforms_ids.array'      => trans('validations/api/admin/csau/category/activity/store.platforms_ids.array'),
            'platforms_ids.*.required' => trans('validations/api/admin/csau/category/activity/store.platforms_ids.*.required'),
            'platforms_ids.*.integer'  => trans('validations/api/admin/csau/category/activity/store.platforms_ids.*.integer'),
            'platforms_ids.*.exists'   => trans('validations/api/admin/csau/category/activity/store.platforms_ids.*.exists'),
            'visible.required'         => trans('validations/api/admin/csau/category/activity/store.visible.required'),
            'visible.boolean'          => trans('validations/api/admin/csau/category/activity/store.visible.boolean')
        ];
    }
}
