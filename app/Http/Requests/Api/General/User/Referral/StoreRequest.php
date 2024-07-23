<?php

namespace App\Http\Requests\Api\General\User\Referral;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\General\User\Referral
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'parent_id' => 'required|integer|exists:users,id',
            'child_id'  => 'required|integer|exists:users,id',
            'active'    => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'parent_id.required' => trans('validations/api/general/user/referral/store.parent_id.required'),
            'parent_id.integer'  => trans('validations/api/general/user/referral/store.parent_id.integer'),
            'parent_id.exists'   => trans('validations/api/general/user/referral/store.parent_id.exists'),
            'child_id.required'  => trans('validations/api/general/user/referral/store.child_id.required'),
            'child_id.integer'   => trans('validations/api/general/user/referral/store.child_id.integer'),
            'child_id.exists'    => trans('validations/api/general/user/referral/store.child_id.exists'),
            'active.required'    => trans('validations/api/general/user/referral/store.active.required'),
            'active.boolean'     => trans('validations/api/general/user/referral/store.active.boolean')
        ];
    }
}
