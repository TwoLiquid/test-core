<?php

namespace App\Http\Requests\Api\General\Vybe\Step;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateFifthStepChangesRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\Step
 */
class UpdateFifthStepChangesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            // 5-th step
            'access_id'       => 'integer|between:1,2|nullable',
            'access_password' => 'string|nullable',
            'showcase_id'     => 'integer|between:1,2|nullable',
            'status_id'       => 'integer|in:1,2,5|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            // 5-th step
            'access_id.integer'      => trans('validations/api/general/vybe/step/updateFifthStepChanges.access_id.integer'),
            'access_id.between'      => trans('validations/api/general/vybe/step/updateFifthStepChanges.access_id.between'),
            'access_password.string' => trans('validations/api/general/vybe/step/updateFifthStepChanges.access_password.string'),
            'showcase_id.integer'    => trans('validations/api/general/vybe/step/updateFifthStepChanges.showcase_id.integer'),
            'showcase_id.between'    => trans('validations/api/general/vybe/step/updateFifthStepChanges.showcase_id.between'),
            'status_id.integer'      => trans('validations/api/general/vybe/step/updateFifthStepChanges.status_id.integer'),
            'status_id.in'           => trans('validations/api/general/vybe/step/updateFifthStepChanges.status_id.in')
        ];
    }
}
