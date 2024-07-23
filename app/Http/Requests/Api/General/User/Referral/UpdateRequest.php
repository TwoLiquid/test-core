<?php

namespace App\Http\Requests\Api\General\User\Referral;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\General\User\Referral
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'parent_id' => 'integer|exists:users,id|nullable',
            'child_id'  => 'integer|exists:users,id|nullable',
            'active'    => 'boolean|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'parent_id.integer' => trans('validations/api/general/user/referral/update.parent_id.integer'),
            'parent_id.exists'  => trans('validations/api/general/user/referral/update.parent_id.exists'),
            'child_id.integer'  => trans('validations/api/general/user/referral/update.child_id.integer'),
            'child_id.exists'   => trans('validations/api/general/user/referral/update.child_id.exists'),
            'active.boolean'    => trans('validations/api/general/user/referral/update.active.boolean')
        ];
    }
}
