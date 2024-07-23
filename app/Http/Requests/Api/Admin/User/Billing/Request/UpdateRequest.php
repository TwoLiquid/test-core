<?php

namespace App\Http\Requests\Api\Admin\User\Billing\Request;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Billing\Request
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'country_place_status_id' => 'required|integer|in:2,3',
            'toast_message_text'      => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'country_place_status_id.required' => trans('validations/api/admin/user/billing/changeRequest/update.country_place_status_id.required'),
            'country_place_status_id.integer'  => trans('validations/api/admin/user/billing/changeRequest/update.country_place_status_id.integer'),
            'country_place_status_id.in'       => trans('validations/api/admin/user/billing/changeRequest/update.country_place_status_id.in'),
            'toast_message_text.string'        => trans('validations/api/admin/user/billing/changeRequest/update.toast_message_text.string')
        ];
    }
}
