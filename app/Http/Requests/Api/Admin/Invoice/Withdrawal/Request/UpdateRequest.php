<?php

namespace App\Http\Requests\Api\Admin\Invoice\Withdrawal\Request;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Invoice\Withdrawal\Request
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'method_id'          => 'integer|exists:payment_methods,id|nullable',
            'status_id'          => 'required|integer|in:2,3',
            'toast_message_text' => 'required_if:status_id,==,2|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'method_id.integer'              => trans('validations/api/admin/invoice/withdrawal/request/update.method_id.integer'),
            'method_id.exists'               => trans('validations/api/admin/invoice/withdrawal/request/update.method_id.exists'),
            'status_id.required'             => trans('validations/api/admin/invoice/withdrawal/request/update.status_id.required'),
            'status_id.integer'              => trans('validations/api/admin/invoice/withdrawal/request/update.status_id.integer'),
            'status_id.in'                   => trans('validations/api/admin/invoice/withdrawal/request/update.status_id.in'),
            'toast_message_text.required_if' => trans('validations/api/admin/invoice/withdrawal/request/update.toast_message_text.required_if'),
            'toast_message_text.string'      => trans('validations/api/admin/invoice/withdrawal/request/update.toast_message_text.string')
        ];
    }
}
