<?php

namespace App\Http\Requests\Api\General\Vybe\UnpublishRequest;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\UnpublishRequest
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
            'message.string' => trans('validations/api/vybe/unpublishRequest/update.message.string')
        ];
    }
}
