<?php

namespace App\Http\Requests\Api\Admin\User\Request\Profile;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Request\Profile
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'account_status_status_id' => 'integer|in:2,3|nullable',
            'username_status_id'       => 'integer|in:2,3|nullable',
            'birth_date_status_id'     => 'integer|in:2,3|nullable',
            'description_status_id'    => 'integer|in:2,3|nullable',
            'voice_sample_status_id'   => 'integer|in:2,3|nullable',
            'avatar_status_id'         => 'integer|in:2,3|nullable',
            'background_status_id'     => 'integer|in:2,3|nullable',
            'album_status_id'          => 'integer|in:2,3|nullable',
            'toast_message_text'       => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'account_status_status_id.integer' => trans('validations/api/admin/user/request/profile/update.account_status_status_id.integer'),
            'account_status_status_id.in'      => trans('validations/api/admin/user/request/profile/update.account_status_status_id.in'),
            'username_status_id.integer'       => trans('validations/api/admin/user/request/profile/update.username_status_id.integer'),
            'username_status_id.in'            => trans('validations/api/admin/user/request/profile/update.username_status_id.in'),
            'birth_date_status_id.integer'     => trans('validations/api/admin/user/request/profile/update.birth_date_status_id.integer'),
            'birth_date_status_id.in'          => trans('validations/api/admin/user/request/profile/update.birth_date_status_id.in'),
            'description_status_id.integer'    => trans('validations/api/admin/user/request/profile/update.description_status_id.integer'),
            'description_status_id.in'         => trans('validations/api/admin/user/request/profile/update.description_status_id.in'),
            'voice_sample_status_id.integer'   => trans('validations/api/admin/user/request/profile/update.voice_sample_status_id.integer'),
            'voice_sample_status_id.in'        => trans('validations/api/admin/user/request/profile/update.voice_sample_status_id.in'),
            'avatar_status_id.integer'         => trans('validations/api/admin/user/request/profile/update.avatar_status_id.integer'),
            'avatar_status_id.in'              => trans('validations/api/admin/user/request/profile/update.avatar_status_id.in'),
            'background_status_id.in'          => trans('validations/api/admin/user/request/profile/update.background_status_id.in'),
            'album_status_id.integer'          => trans('validations/api/admin/user/request/profile/update.album_status_id.integer'),
            'album_status_id.in'               => trans('validations/api/admin/user/request/profile/update.album_status_id.in'),
            'toast_message_text.string'        => trans('validations/api/admin/user/request/profile/update.toast_message_text.string')
        ];
    }
}
