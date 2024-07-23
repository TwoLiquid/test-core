<?php

namespace App\Http\Requests\Api\General\Setting\Account;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ChangeTimezoneRequest
 *
 * @package App\Http\Requests\Api\General\Setting\Account
 */
class ChangeTimezoneRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'place_id' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'place_id.required' => trans('validations/api/general/setting/account/changeTimezone.place_id.required'),
            'place_id.string'   => trans('validations/api/general/setting/account/changeTimezone.place_id.string')
        ];
    }
}
