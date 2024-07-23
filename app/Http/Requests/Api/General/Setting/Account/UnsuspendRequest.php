<?php

namespace App\Http\Requests\Api\General\Setting\Account;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UnsuspendRequest
 *
 * @package App\Http\Requests\Api\General\Setting\Account
 */
class UnsuspendRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'reason' => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'reason.string' => trans('validations/api/general/setting/account/unsuspendRequest.reason.string')
        ];
    }
}
