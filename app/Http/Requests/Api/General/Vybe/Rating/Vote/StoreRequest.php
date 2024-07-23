<?php

namespace App\Http\Requests\Api\General\Vybe\Rating\Vote;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\Rating\Vote
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'vybe_id' => 'required|integer|exists:vybes,id',
            'user_id' => 'required|integer|exists:users,id',
            'rating'  => 'required|integer'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'vybe_id.required' => trans('validations/api/general/vybe/rating/vote/store.vybe_id.required'),
            'vybe_id.integer'  => trans('validations/api/general/vybe/rating/vote/store.vybe_id.integer'),
            'vybe_id.exists'   => trans('validations/api/general/vybe/rating/vote/store.vybe_id.exists'),
            'user_id.required' => trans('validations/api/general/vybe/rating/vote/store.user_id.required'),
            'user_id.integer'  => trans('validations/api/general/vybe/rating/vote/store.user_id.integer'),
            'user_id.exists'   => trans('validations/api/general/vybe/rating/vote/store.user_id.exists'),
            'rating.required'  => trans('validations/api/general/vybe/rating/vote/store.rating.required'),
            'rating.integer'   => trans('validations/api/general/vybe/rating/vote/store.rating.integer')
        ];
    }
}
