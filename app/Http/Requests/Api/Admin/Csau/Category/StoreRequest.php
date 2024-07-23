<?php

namespace App\Http\Requests\Api\Admin\Csau\Category;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category
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
            'name.required'         => trans('validations/api/admin/csau/category/store.name.required'),
            'name.array'            => trans('validations/api/admin/csau/category/store.name.array'),
            'visible.required'      => trans('validations/api/admin/csau/category/store.visible.required'),
            'visible.boolean'       => trans('validations/api/admin/csau/category/store.visible.boolean'),
            'icon.array'            => trans('validations/api/admin/csau/category/store.icon.array'),
            'icon.content.string'   => trans('validations/api/admin/csau/category/store.icon.content.string'),
            'icon.extension.string' => trans('validations/api/admin/csau/category/store.icon.extension.string'),
            'icon.mime.string'      => trans('validations/api/admin/csau/category/store.icon.mime.string')
        ];
    }
}
