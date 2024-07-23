<?php

namespace App\Http\Requests\Api\Admin\User\IdVerification;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\User\IdVerification
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'verification_status_id' => 'required|integer|in:1,2,3',
            'verification_suspended' => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'verification_status_status_id.required' => trans('validations/api/admin/user/idVerification/update.verification_status_status_id.required'),
            'verification_status_status_id.integer'  => trans('validations/api/admin/user/idVerification/update.verification_status_status_id.integer'),
            'verification_status_status_id.in'       => trans('validations/api/admin/user/idVerification/update.verification_status_status_id.in'),
            'verification_suspended.required'        => trans('validations/api/admin/user/idVerification/update.verification_suspended.required'),
            'verification_suspended.boolean'         => trans('validations/api/admin/user/idVerification/update.verification_suspended.boolean')
        ];
    }
}
