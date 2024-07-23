<?php

namespace App\Http\Requests\Api\General\Vybe\UnsuspendRequest;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\UnsuspendRequest
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'message' => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'message.string' => trans('validations/api/vybe/unsuspendRequest/update.message.string')
        ];
    }
}
