<?php

namespace App\Http\Requests\Api\General\Setting\IdVerification;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\Setting\IdVerification
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'content'       => 'required|string',
            'original_name' => 'string|nullable',
            'extension'     => 'required|string',
            'mime'          => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'content.required'     => trans('validations/api/general/setting/idVerification/update.content.required'),
            'content.string'       => trans('validations/api/general/setting/idVerification/update.content.string'),
            'original_name.string' => trans('validations/api/general/setting/idVerification/update.original_name.string'),
            'extension.required'   => trans('validations/api/general/setting/idVerification/update.extension.required'),
            'extension.string'     => trans('validations/api/general/setting/idVerification/update.extension.string'),
            'mime.required'        => trans('validations/api/general/setting/idVerification/update.mime.required'),
            'mime.string'          => trans('validations/api/general/setting/idVerification/update.mime.string')
        ];
    }
}
