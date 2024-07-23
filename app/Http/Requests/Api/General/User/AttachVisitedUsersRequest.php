<?php

namespace App\Http\Requests\Api\General\User;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class AttachVisitedUsersRequest
 *
 * @package App\Http\Requests\Api\General\User
 */
class AttachVisitedUsersRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'ids'       => 'required|array',
            'ids.*'     => 'required|integer|exists:users,id',
            'detaching' => 'boolean|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'ids.required'      => trans('validations/api/general/user/attachVisitedUsers.ids.required'),
            'ids.array'         => trans('validations/api/general/user/attachVisitedUsers.ids.array'),
            'ids.*.required'    => trans('validations/api/general/user/attachVisitedUsers.ids.*.required'),
            'ids.*.integer'     => trans('validations/api/general/user/attachVisitedUsers.ids.*.integer'),
            'ids.*.exists'      => trans('validations/api/general/user/attachVisitedUsers.ids.*.exists'),
            'detaching.boolean' => trans('validations/api/general/user/attachVisitedUsers.detaching.boolean')
        ];
    }
}
