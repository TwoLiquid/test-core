<?php

namespace App\Http\Requests\Api\General\Vybe\Step;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateFifthStepNext
 *
 * @package App\Http\Requests\Api\General\Vybe\Step
 */
class UpdateFifthStepNext extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            // 5-th step
            'access_id'       => 'required|integer|between:1,2',
            'access_password' => 'string|nullable',
            'showcase_id'     => 'required|integer|between:1,2',
            'status_id'       => 'required|integer|in:1,2,5'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            // 5-th step
            'access_id.required'     => trans('validations/api/general/vybe/step/updateFifthStepNext.access_id.required'),
            'access_id.integer'      => trans('validations/api/general/vybe/step/updateFifthStepNext.access_id.integer'),
            'access_id.between'      => trans('validations/api/general/vybe/step/updateFifthStepNext.access_id.between'),
            'access_password.string' => trans('validations/api/general/vybe/step/updateFifthStepNext.access_password.string'),
            'showcase_id.required'   => trans('validations/api/general/vybe/step/updateFifthStepNext.showcase_id.required'),
            'showcase_id.integer'    => trans('validations/api/general/vybe/step/updateFifthStepNext.showcase_id.integer'),
            'showcase_id.between'    => trans('validations/api/general/vybe/step/updateFifthStepNext.showcase_id.between'),
            'status_id.required'     => trans('validations/api/general/vybe/step/updateFifthStepNext.status_id.required'),
            'status_id.integer'      => trans('validations/api/general/vybe/step/updateFifthStepNext.status_id.integer'),
            'status_id.in'           => trans('validations/api/general/vybe/step/updateFifthStepNext.status_id.in')
        ];
    }
}
