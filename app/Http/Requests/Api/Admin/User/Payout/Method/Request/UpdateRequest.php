<?php

namespace App\Http\Requests\Api\Admin\User\Payout\Method\Request;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Payout\Method\Request
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'method_id'          => 'required|integer|exists:payment_methods,id',
            'request_status_id'  => 'required|integer|in:2,3',
            'toast_message_text' => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'method_id.required'         => trans('validations/api/admin/user/payout/method/request/update.method_id.required'),
            'method_id.integer'          => trans('validations/api/admin/user/payout/method/request/update.method_id.integer'),
            'method_id.exists'           => trans('validations/api/admin/user/payout/method/request/update.method_id.exists'),
            'request_status_id.required' => trans('validations/api/admin/user/payout/method/request/update.request_status_id.required'),
            'request_status_id.integer'  => trans('validations/api/admin/user/payout/method/request/update.request_status_id.integer'),
            'request_status_id.in'       => trans('validations/api/admin/user/payout/method/request/update.request_status_id.in'),
            'toast_message_text.string'  => trans('validations/api/admin/user/payout/method/request/update.toast_message_text.string')
        ];
    }
}
