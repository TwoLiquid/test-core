<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Subcategory;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Subcategory
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
            'category_id'    => 'required|integer|exists:categories,id',
            'visible'        => 'required|boolean',
            'icon'           => 'array|nullable',
            'icon.content'   => 'string|sometimes',
            'icon.extension' => 'string|sometimes',
            'icon.mime'      => 'string|sometimes'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.required'         => trans('validations/api/admin/csau/category/subcategory/store.name.required'),
            'name.array'            => trans('validations/api/admin/csau/category/subcategory/store.name.array'),
            'category_id.required'  => trans('validations/api/admin/csau/category/subcategory/store.category_id.required'),
            'category_id.integer'   => trans('validations/api/admin/csau/category/subcategory/store.category_id.integer'),
            'category_id.exists'    => trans('validations/api/admin/csau/category/subcategory/store.category_id.exists'),
            'visible.required'      => trans('validations/api/admin/csau/category/subcategory/store.visible.required'),
            'visible.boolean'       => trans('validations/api/admin/csau/category/subcategory/store.visible.boolean'),
            'icon.array'            => trans('validations/api/admin/csau/category/subcategory/store.icon.array'),
            'icon.content.string'   => trans('validations/api/admin/csau/category/subcategory/store.icon.content.string'),
            'icon.extension.string' => trans('validations/api/admin/csau/category/subcategory/store.icon.extension.string'),
            'icon.mime.string'      => trans('validations/api/admin/csau/category/subcategory/store.icon.mime.string')
        ];
    }
}
