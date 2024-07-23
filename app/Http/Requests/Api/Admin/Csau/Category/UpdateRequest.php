<?php

namespace App\Http\Requests\Api\Admin\Csau\Category;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category
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
            'visible'        => 'boolean|nullable',
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
            'name.array'            => trans('validations/api/admin/csau/category/update.name.array'),
            'visible.boolean'       => trans('validations/api/admin/csau/category/update.visible.boolean'),
            'icon.array'            => trans('validations/api/admin/csau/category/update.icon.array'),
            'icon.content.string'   => trans('validations/api/admin/csau/category/update.icon.content.string'),
            'icon.extension.string' => trans('validations/api/admin/csau/category/update.icon.extension.string'),
            'icon.mime.string'      => trans('validations/api/admin/csau/category/update.icon.mime.string')
        ];
    }
}
