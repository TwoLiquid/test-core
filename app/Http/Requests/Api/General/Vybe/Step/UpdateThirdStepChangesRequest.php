<?php

namespace App\Http\Requests\Api\General\Vybe\Step;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateThirdStepChangesRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\Step
 */
class UpdateThirdStepChangesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            // 3-rd step
            'schedules'                 => 'array|nullable',
            'schedules.*.datetime_from' => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'schedules.*.datetime_to'   => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
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
            'schedules.array'                       => trans('validations/api/general/vybe/step/updateThirdStepChanges.schedules.array'),
            'schedules.*.datetime_from.date_format' => trans('validations/api/general/vybe/step/updateThirdStepChanges.schedules.*.datetime_from.date_format'),
            'schedules.*.datetime_to.date_format'   => trans('validations/api/general/vybe/step/updateThirdStepChanges.schedules.*.datetime_to.date_format'),
            'order_advance.integer'                 => trans('validations/api/general/vybe/step/updateThirdStepChanges.order_advance.integer')
        ];
    }
}
