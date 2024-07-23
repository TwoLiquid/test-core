<?php

namespace App\Http\Requests\Api\Admin\Vybe\DeletionRequest;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\Vybe\DeletionRequest
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'status_status_id'   => 'required|integer|in:2,3',
            'toast_message_text' => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'status_status_id.required' => trans('validations/api/admin/vybe/deletionRequest/update.status_status_id.required'),
            'status_status_id.integer'  => trans('validations/api/admin/vybe/deletionRequest/update.status_status_id.integer'),
            'status_status_id.in'       => trans('validations/api/admin/vybe/deletionRequest/update.status_status_id.in'),
            'toast_message_text.string' => trans('validations/api/admin/vybe/deletionRequest/update.toast_message_text.string')
        ];
    }
}
