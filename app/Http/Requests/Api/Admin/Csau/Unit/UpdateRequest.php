<?php

namespace App\Http\Requests\Api\Admin\Csau\Unit;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Unit
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
            'name.array'       => trans('validations/api/admin/csau/unit/update.name.array'),
            'duration.integer' => trans('validations/api/admin/csau/unit/update.duration.integer'),
            'visible.boolean'  => trans('validations/api/admin/csau/unit/update.visible.boolean')
        ];
    }
}
