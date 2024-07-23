<?php

namespace App\Http\Requests\Api\Admin\User\Request\Deactivation;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Request\Deactivation
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'account_status_status_id' => 'required|nullable|in:2,3',
            'toast_message_text'       => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'account_status_status_id.required' => trans('validations/api/admin/user/request/deactivation/update.account_status_status_id.required'),
            'account_status_status_id.integer'  => trans('validations/api/admin/user/request/deactivation/update.account_status_status_id.integer'),
            'account_status_status_id.in'       => trans('validations/api/admin/user/request/deactivation/update.account_status_status_id.in'),
            'toast_message_text.string'         => trans('validations/api/admin/user/request/deactivation/update.toast_message_text.string')
        ];
    }
}
