<?php

namespace App\Http\Requests\Api\General\Vybe\Step;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateThirdStepNextRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\Step
 */
class UpdateThirdStepNextRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            // 3-rd step
            'schedules'                 => 'required|array',
            'schedules.*.datetime_from' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'schedules.*.datetime_to'   => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'order_advance'             => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            // 3-rd step
            'schedules.required'                    => trans('validations/api/general/vybe/step/updateThirdStepNext.schedules.required'),
            'schedules.array'                       => trans('validations/api/general/vybe/step/updateThirdStepNext.schedules.array'),
            'schedules.*.datetime_from.required'    => trans('validations/api/general/vybe/step/updateThirdStepNext.schedules.*.datetime_from.required'),
            'schedules.*.datetime_from.date_format' => trans('validations/api/general/vybe/step/updateThirdStepNext.schedules.*.datetime_from.date_format'),
            'schedules.*.datetime_to.required'      => trans('validations/api/general/vybe/step/updateThirdStepNext.schedules.*.datetime_to.required'),
            'schedules.*.datetime_to.date_format'   => trans('validations/api/general/vybe/step/updateThirdStepNext.schedules.*.datetime_to.date_format'),
            'order_advance.integer'                 => trans('validations/api/general/vybe/step/updateThirdStepNext.order_advance.integer')
        ];
    }
}
