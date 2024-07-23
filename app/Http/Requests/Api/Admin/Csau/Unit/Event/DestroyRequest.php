<?php

namespace App\Http\Requests\Api\Admin\Csau\Unit\Event;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class DestroyRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Unit\Event
 */
class DestroyRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'password' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'password.required' => trans('validations/api/admin/csau/unit/event/destroy.password.required'),
            'password.string'   => trans('validations/api/admin/csau/unit/event/destroy.password.string')
        ];
    }
}
