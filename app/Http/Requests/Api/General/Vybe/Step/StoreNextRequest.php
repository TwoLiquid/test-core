<?php

namespace App\Http\Requests\Api\General\Vybe\Step;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreNextRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\Step
 */
class StoreNextRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            // 1-st step
            'title'                  => 'required|string|min:10|max:64',
            'category_id'            => 'integer|exists:categories,id|nullable',
            'category_suggestion'    => 'string|nullable',
            'subcategory_id'         => 'integer|exists:categories,id|nullable',
            'subcategory_suggestion' => 'string|nullable',
            'devices_ids'            => 'array|nullable',
            'devices_ids.*'          => 'required|integer|exists:devices,id',
            'device_suggestion'      => 'string|nullable',
            'activity_id'            => 'integer|exists:activities,id|nullable',
            'activity_suggestion'    => 'string|nullable',
            'period_id'              => 'required|integer|between:1,2',
            'user_count'             => 'required|integer'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            // 1-st step
            'title.required'                => trans('validations/api/general/vybe/step/storeNext.title.required'),
            'title.string'                  => trans('validations/api/general/vybe/step/storeNext.title.string'),
            'title.min'                     => trans('validations/api/general/vybe/step/storeNext.title.min'),
            'title.max'                     => trans('validations/api/general/vybe/step/storeNext.title.max'),
            'category_id.*.integer'         => trans('validations/api/general/vybe/step/storeNext.category_id.integer'),
            'category_id.*.exists'          => trans('validations/api/general/vybe/step/storeNext.category_id.exists'),
            'category_suggestion.string'    => trans('validations/api/general/vybe/step/storeNext.category_suggestion.string'),
            'subcategory_id.*.integer'      => trans('validations/api/general/vybe/step/storeNext.subcategory_id.integer'),
            'subcategory_id.*.exists'       => trans('validations/api/general/vybe/step/storeNext.subcategory_id.exists'),
            'subcategory_suggestion.string' => trans('validations/api/general/vybe/step/storeNext.subcategory_suggestion.string'),
            'devices_ids.array'             => trans('validations/api/general/vybe/step/storeNext.devices_ids.array'),
            'devices_ids.*.required'        => trans('validations/api/general/vybe/step/storeNext.devices_ids.*.required'),
            'devices_ids.*.integer'         => trans('validations/api/general/vybe/step/storeNext.devices_ids.*.integer'),
            'devices_ids.*.exists'          => trans('validations/api/general/vybe/step/storeNext.devices_ids.*.exists'),
            'device_suggestion.string'      => trans('validations/api/general/vybe/step/storeNext.device_suggestion.string'),
            'activity_id.*.integer'         => trans('validations/api/general/vybe/step/storeNext.activity_id.integer'),
            'activity_id.*.exists'          => trans('validations/api/general/vybe/step/storeNext.activity_id.exists'),
            'activity_suggestion.string'    => trans('validations/api/general/vybe/step/storeNext.activity_suggestion.string'),
            'period_id.required'            => trans('validations/api/general/vybe/step/storeNext.period_id.required'),
            'period_id.integer'             => trans('validations/api/general/vybe/step/storeNext.period_id.integer'),
            'user_count.required'           => trans('validations/api/general/vybe/step/storeNext.user_count.required'),
            'user_count.integer'            => trans('validations/api/general/vybe/step/storeNext.user_count.integer')
        ];
    }
}
