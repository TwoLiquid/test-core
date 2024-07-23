<?php

namespace App\Http\Requests\Api\General\Vybe\Step;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateSecondStepNextRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\Step
 */
class UpdateSecondStepNextRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            // 2-nd step
            'appearance_cases'                             => 'required|array',
            'appearance_cases.voice_chat'                  => 'array|nullable',
            'appearance_cases.voice_chat.price'            => 'required|numeric|sometimes',
            'appearance_cases.voice_chat.description'      => 'required|string|sometimes',
            'appearance_cases.voice_chat.unit_id'          => 'integer|exists:units,id|nullable',
            'appearance_cases.voice_chat.unit_suggestion'  => 'string|nullable',
            'appearance_cases.voice_chat.platforms_ids'    => 'array|nullable',
            'appearance_cases.voice_chat.platforms_ids.*'  => 'required|integer|exists:platforms,id|sometimes',
            'appearance_cases.voice_chat.enabled'          => 'required|boolean|sometimes',
            'appearance_cases.video_chat'                  => 'array|nullable',
            'appearance_cases.video_chat.price'            => 'required|numeric|sometimes',
            'appearance_cases.video_chat.description'      => 'required|string|sometimes',
            'appearance_cases.video_chat.unit_id'          => 'integer|exists:units,id|nullable',
            'appearance_cases.video_chat.unit_suggestion'  => 'string|nullable',
            'appearance_cases.video_chat.platforms_ids'    => 'array|nullable',
            'appearance_cases.video_chat.platforms_ids.*'  => 'required|integer|exists:platforms,id|sometimes',
            'appearance_cases.video_chat.enabled'          => 'required|boolean|sometimes',
            'appearance_cases.real_life'                   => 'array|nullable',
            'appearance_cases.real_life.price'             => 'required|numeric|sometimes',
            'appearance_cases.real_life.description'       => 'required|string|sometimes',
            'appearance_cases.real_life.unit_id'           => 'integer|exists:units,id|nullable',
            'appearance_cases.real_life.unit_suggestion'   => 'string|nullable',
            'appearance_cases.real_life.same_location'     => 'required|boolean|sometimes',
            'appearance_cases.real_life.city_place_id'     => 'string|nullable',
            'appearance_cases.real_life.enabled'           => 'required|boolean|sometimes'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            // 2-nd step
            'appearance_cases.required'                            => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.required'),
            'appearance_cases.array'                               => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.array'),
            'appearance_cases.voice_chat.array'                    => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.array'),
            'appearance_cases.voice_chat.price.required'           => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.price.required'),
            'appearance_cases.voice_chat.price.numeric'            => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.price.numeric'),
            'appearance_cases.voice_chat.description.required'     => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.description.required'),
            'appearance_cases.voice_chat.description.string'       => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.description.string'),
            'appearance_cases.voice_chat.unit_id.integer'          => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.unit_id.integer'),
            'appearance_cases.voice_chat.unit_id.exists'           => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.unit_id.exists'),
            'appearance_cases.voice_chat.unit_suggestion.string'   => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.unit_suggestion.string'),
            'appearance_cases.voice_chat.platforms_ids.array'      => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.array'),
            'appearance_cases.voice_chat.platforms_ids.*.required' => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.*.required'),
            'appearance_cases.voice_chat.platforms_ids.*.integer'  => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.*.integer'),
            'appearance_cases.voice_chat.platforms_ids.*.exists'   => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.platforms_ids.*.exists'),
            'appearance_cases.voice_chat.enabled.required'         => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.enabled.required'),
            'appearance_cases.voice_chat.enabled.boolean'          => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.voice_chat.enabled.boolean'),
            'appearance_cases.video_chat.array'                    => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.array'),
            'appearance_cases.video_chat.price.required'           => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.price.required'),
            'appearance_cases.video_chat.price.numeric'            => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.price.numeric'),
            'appearance_cases.video_chat.description.required'     => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.description.required'),
            'appearance_cases.video_chat.description.string'       => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.description.string'),
            'appearance_cases.video_chat.unit_id.integer'          => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.unit_id.integer'),
            'appearance_cases.video_chat.unit_id.exists'           => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.unit_id.exists'),
            'appearance_cases.video_chat.unit_suggestion.string'   => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.unit_suggestion.string'),
            'appearance_cases.video_chat.platforms_ids.array'      => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.platforms_ids.array'),
            'appearance_cases.video_chat.platforms_ids.*.required' => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.platforms_ids.*.required'),
            'appearance_cases.video_chat.platforms_ids.*.integer'  => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.platforms_ids.*.integer'),
            'appearance_cases.video_chat.platforms_ids.*.exists'   => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.platforms_ids.*.exists'),
            'appearance_cases.video_chat.enabled.required'         => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.enabled.required'),
            'appearance_cases.video_chat.enabled.boolean'          => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.video_chat.enabled.boolean'),
            'appearance_cases.real_life.array'                     => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.array'),
            'appearance_cases.real_life.price.required'            => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.price.required'),
            'appearance_cases.real_life.price.numeric'             => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.price.numeric'),
            'appearance_cases.real_life.description.required'      => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.description.required'),
            'appearance_cases.real_life.description.string'        => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.description.string'),
            'appearance_cases.real_life.unit_id.string'            => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.unit_id.string'),
            'appearance_cases.real_life.unit_id.exists'            => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.unit_id.exists'),
            'appearance_cases.real_life.unit_suggestion.string'    => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.unit_suggestion.string'),
            'appearance_cases.real_life.same_location.required'    => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.same_location.required'),
            'appearance_cases.real_life.same_location.boolean'     => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.same_location.boolean'),
            'appearance_cases.real_life.city_place_id.string'      => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.city_place_id.string'),
            'appearance_cases.real_life.enabled.required'          => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.enabled.required'),
            'appearance_cases.real_life.enabled.boolean'           => trans('validations/api/general/vybe/step/updateSecondStepNext.appearance_cases.real_life.enabled.boolean')
        ];
    }
}
