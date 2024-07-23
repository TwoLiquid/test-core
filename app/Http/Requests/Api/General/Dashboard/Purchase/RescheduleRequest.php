<?php

namespace App\Http\Requests\Api\General\Dashboard\Purchase;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class RescheduleRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Purchase
 */
class RescheduleRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'datetime_from' => 'required|date_format:Y-m-d\TH:i:s.v\Z',
            'datetime_to'   => 'required|date_format:Y-m-d\TH:i:s.v\Z'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'datetime_from.required'    => trans('validations/api/general/dashboard/purchase/reschedule.datetime_from.required'),
            'datetime_from.date_format' => trans('validations/api/general/dashboard/purchase/reschedule.datetime_from.date_format'),
            'datetime_to.required'      => trans('validations/api/general/dashboard/purchase/reschedule.datetime_to.required'),
            'datetime_to.date_format'   => trans('validations/api/general/dashboard/purchase/reschedule.datetime_to.date_format')
        ];
    }
}
