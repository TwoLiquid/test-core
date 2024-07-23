<?php

namespace App\Http\Requests\Api\General\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class DetachFavoriteActivitiesRequest
 *
 * @package App\Http\Requests\Api\General\Auth
 */
class DetachFavoriteActivitiesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'required|integer|exists:activities,id'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'ids.required'   => trans('validations/api/general/auth/detachFavoriteActivities.ids.required'),
            'ids.array'      => trans('validations/api/general/auth/detachFavoriteActivities.ids.array'),
            'ids.*.required' => trans('validations/api/general/auth/detachFavoriteActivities.ids.*.required'),
            'ids.*.integer'  => trans('validations/api/general/auth/detachFavoriteActivities.ids.*.integer'),
            'ids.*.exists'   => trans('validations/api/general/auth/detachFavoriteActivities.ids.*.exists')
        ];
    }
}
