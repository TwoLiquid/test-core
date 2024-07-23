<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Unit;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Unit
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
            'activity_id' => 'required|integer|exists:activities,id',
            'duration'    => 'required|integer',
            'visible'     => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.required'        => trans('validations/api/admin/csau/category/unit/store.name.required'),
            'name.array'           => trans('validations/api/admin/csau/category/unit/store.name.array'),
            'activity_id.required' => trans('validations/api/admin/csau/category/unit/store.activity_id.required'),
            'activity_id.integer'  => trans('validations/api/admin/csau/category/unit/store.activity_id.integer'),
            'activity_id.exists'   => trans('validations/api/admin/csau/category/unit/store.activity_id.exists'),
            'duration.required'    => trans('validations/api/admin/csau/category/unit/store.duration.required'),
            'duration.string'      => trans('validations/api/admin/csau/category/unit/store.duration.string'),
            'visible.required'     => trans('validations/api/admin/csau/category/unit/store.visible.required'),
            'visible.boolean'      => trans('validations/api/admin/csau/category/unit/store.visible.boolean')
        ];
    }
}
