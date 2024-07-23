<?php

namespace App\Http\Requests\Api\Admin\Csau\Suggestion\Device;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Suggestion\Device
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'suggestion' => 'string|nullable',
            'status_id'  => 'required|integer|between:2,3',
            'visible'    => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'suggestion.string'  => trans('validations/api/admin/csau/suggestion/device/update.suggestion.string'),
            'status_id.required' => trans('validations/api/admin/csau/suggestion/device/update.status_id.required'),
            'status_id.integer'  => trans('validations/api/admin/csau/suggestion/device/update.status_id.integer'),
            'status_id.between'  => trans('validations/api/admin/csau/suggestion/device/update.status_id.between'),
            'visible.required'   => trans('validations/api/admin/csau/suggestion/device/update.visible.required'),
            'visible.boolean'    => trans('validations/api/admin/csau/suggestion/device/update.visible.boolean')
        ];
    }
}
