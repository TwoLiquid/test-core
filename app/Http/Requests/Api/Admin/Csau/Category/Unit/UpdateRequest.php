<?php

namespace App\Http\Requests\Api\Admin\Csau\Category\Unit;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category\Unit
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
            'duration' => 'integer|nullable',
            'visible'  => 'boolean|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.array'      => trans('validations/api/admin/csau/category/unit/update.name.array'),
            'duration.string' => trans('validations/api/admin/csau/category/unit/update.duration.string'),
            'visible.boolean' => trans('validations/api/admin/csau/category/unit/update.visible.boolean')
        ];
    }
}
