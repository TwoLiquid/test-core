<?php

namespace App\Http\Requests\Api\Admin\User\IdVerification\Request;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\User\IdVerificationRequest\Request
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'verification_status_status_id' => 'required|integer|in:2,3',
            'toast_message_text'            => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'verification_status_status_id.required' => trans('validations/api/admin/user/idVerification/request/update.verification_status_status_id.required'),
            'verification_status_status_id.integer'  => trans('validations/api/admin/user/idVerification/request/update.verification_status_status_id.integer'),
            'verification_status_status_id.in'       => trans('validations/api/admin/user/idVerification/request/update.verification_status_status_id.in'),
            'toast_message_text.string'              => trans('validations/api/admin/user/idVerification/request/update.toast_message_text.string')
        ];
    }
}
