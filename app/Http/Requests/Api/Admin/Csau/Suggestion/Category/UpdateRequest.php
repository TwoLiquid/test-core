<?php

namespace App\Http\Requests\Api\Admin\Csau\Suggestion\Category;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Suggestion\Category
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'category_suggestion'    => [
                'array',
                'nullable',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'category_status_id'     => 'integer|between:2,3|nullable',
            'subcategory_suggestion' => [
                'array',
                'nullable',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'subcategory_status_id'  => 'integer|between:2,3|nullable',
            'activity_suggestion'    => [
                'array',
                'nullable',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'activity_status_id'     => 'integer|between:2,3|nullable',
            'unit_suggestion'        => [
                'array',
                'nullable',
                new TranslationAcceptableRule(),
                new TranslationRequiredRule()
            ],
            'unit_duration'          => 'integer|nullable',
            'unit_status_id'         => 'integer|between:2,3|nullable',
            'visible'                => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'category_suggestion.array'     => trans('validations/api/admin/csau/suggestion/category/update.category_suggestion.array'),
            'category_status_id.integer'    => trans('validations/api/admin/csau/suggestion/category/update.category_status_id.integer'),
            'category_status_id.between'    => trans('validations/api/admin/csau/suggestion/category/update.category_status_id.between'),
            'subcategory_suggestion.array'  => trans('validations/api/admin/csau/suggestion/category/update.subcategory_suggestion.array'),
            'subcategory_status_id.integer' => trans('validations/api/admin/csau/suggestion/category/update.subcategory_status_id.integer'),
            'subcategory_status_id.between' => trans('validations/api/admin/csau/suggestion/category/update.subcategory_status_id.between'),
            'activity_suggestion.array'     => trans('validations/api/admin/csau/suggestion/category/update.activity_suggestion.array'),
            'activity_status_id.integer'    => trans('validations/api/admin/csau/suggestion/category/update.activity_status_id.integer'),
            'activity_status_id.between'    => trans('validations/api/admin/csau/suggestion/category/update.activity_status_id.between'),
            'unit_suggestion.array'         => trans('validations/api/admin/csau/suggestion/category/update.unit_suggestion.array'),
            'unit_duration.integer'         => trans('validations/api/admin/csau/suggestion/category/update.unit_duration.integer'),
            'unit_status_id.integer'        => trans('validations/api/admin/csau/suggestion/category/update.unit_status_id.integer'),
            'unit_status_id.between'        => trans('validations/api/admin/csau/suggestion/category/update.unit_status_id.between'),
            'visible.required'              => trans('validations/api/admin/csau/suggestion/category/update.visible.required'),
            'visible.boolean'               => trans('validations/api/admin/csau/suggestion/category/update.visible.boolean')
        ];
    }
}
