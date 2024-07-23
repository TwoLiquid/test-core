<?php

namespace App\Http\Requests\Api\Admin\Csau\ActivityTag;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\ActivityTag
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
            'visible_in_category'    => 'boolean|nullable',
            'visible_in_subcategory' => 'boolean|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.array'                     => trans('validations/api/admin/csau/activityTag/update.name.array'),
            'visible_in_category.boolean'    => trans('validations/api/admin/csau/activityTag/update.visible_in_category.boolean'),
            'visible_in_subcategory.boolean' => trans('validations/api/admin/csau/activityTag/update.visible_in_subcategory.boolean')
        ];
    }
}
