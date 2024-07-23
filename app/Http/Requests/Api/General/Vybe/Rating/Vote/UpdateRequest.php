<?php

namespace App\Http\Requests\Api\General\Vybe\Rating\Vote;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\Vybe\Rating\Vote
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'vybe_id' => 'integer|exists:vybes,id|nullable',
            'user_id' => 'integer|exists:users,id|nullable',
            'rating'  => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'vybe_id.integer' => trans('validations/api/general/vybe/rating/vote/update.vybe_id.integer'),
            'vybe_id.exists'  => trans('validations/api/general/vybe/rating/vote/update.vybe_id.exists'),
            'user_id.integer' => trans('validations/api/general/vybe/rating/vote/update.user_id.integer'),
            'user_id.exists'  => trans('validations/api/general/vybe/rating/vote/update.user_id.exists'),
            'rating.integer'  => trans('validations/api/general/vybe/rating/vote/update.rating.integer')
        ];
    }
}
