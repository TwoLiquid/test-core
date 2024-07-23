<?php

namespace App\Http\Requests\Api\Admin\Csau\Unit\Event;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Unit\Event
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
            'visible' => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.required'    => trans('validations/api/admin/csau/unit/event/store.name.required'),
            'name.array'       => trans('validations/api/admin/csau/unit/event/store.name.array'),
            'visible.required' => trans('validations/api/admin/csau/unit/event/store.visible.required'),
            'visible.boolean'  => trans('validations/api/admin/csau/unit/event/store.visible.boolean')
        ];
    }
}
