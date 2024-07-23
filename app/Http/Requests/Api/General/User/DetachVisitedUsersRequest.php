<?php

namespace App\Http\Requests\Api\General\User;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class DetachVisitedUsersRequest
 *
 * @package App\Http\Requests\Api\General\User
 */
class DetachVisitedUsersRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'required|integer|exists:users,id'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'ids.required'   => trans('validations/api/general/user/detachVisitedUsers.ids.required'),
            'ids.array'      => trans('validations/api/general/user/detachVisitedUsers.ids.array'),
            'ids.*.required' => trans('validations/api/general/user/detachVisitedUsers.ids.*.required'),
            'ids.*.integer'  => trans('validations/api/general/user/detachVisitedUsers.ids.*.integer'),
            'ids.*.exists'   => trans('validations/api/general/user/detachVisitedUsers.ids.*.exists')
        ];
    }
}
