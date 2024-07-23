<?php

namespace App\Http\Requests\Api\Admin\Csau\Unit;

use App\Http\Requests\Api\BaseRequest;
use App\Rules\Translation\TranslationAcceptableRule;
use App\Rules\Translation\TranslationRequiredRule;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Unit
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
            'duration' => 'required|integer',
            'visible'  => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.required'     => trans('validations/api/admin/csau/unit/store.name.required'),
            'name.array'        => trans('validations/api/admin/csau/unit/store.name.array'),
            'duration.required' => trans('validations/api/admin/csau/unit/store.duration.required'),
            'duration.integer'  => trans('validations/api/admin/csau/unit/store.duration.integer'),
            'visible.required'  => trans('validations/api/admin/csau/unit/store.visible.required'),
            'visible.boolean'   => trans('validations/api/admin/csau/unit/store.visible.boolean')
        ];
    }
}
