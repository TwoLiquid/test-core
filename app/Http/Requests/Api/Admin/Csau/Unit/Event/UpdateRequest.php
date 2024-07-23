<?php

namespace App\Http\Requests\Api\Admin\Csau\Unit\Event;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Unit\Event
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
            'visible' => 'boolean|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.array'      => trans('validations/api/admin/csau/unit/event/update.name.array'),
            'visible.boolean' => trans('validations/api/admin/csau/unit/event/update.visible.boolean')
        ];
    }
}
