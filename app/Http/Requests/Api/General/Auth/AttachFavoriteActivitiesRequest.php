<?php

namespace App\Http\Requests\Api\General\Auth;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class AttachFavoriteActivitiesRequest
 *
 * @package App\Http\Requests\Api\General\Auth
 */
class AttachFavoriteActivitiesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'ids'       => 'required|array',
            'ids.*'     => 'required|integer|exists:activities,id',
            'detaching' => 'boolean|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'ids.required'      => trans('validations/api/general/auth/attachFavoriteActivities.ids.required'),
            'ids.array'         => trans('validations/api/general/auth/attachFavoriteActivities.ids.array'),
            'ids.*.required'    => trans('validations/api/general/auth/attachFavoriteActivities.ids.*.required'),
            'ids.*.integer'     => trans('validations/api/general/auth/attachFavoriteActivities.ids.*.integer'),
            'ids.*.exists'      => trans('validations/api/general/auth/attachFavoriteActivities.ids.*.exists'),
            'detaching.boolean' => trans('validations/api/general/auth/attachFavoriteActivities.detaching.boolean')
        ];
    }
}
