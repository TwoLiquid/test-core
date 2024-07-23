<?php

namespace App\Http\Requests\Api\Admin\General\Admin;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\General\Admin
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'first_name'       => 'required|string',
            'last_name'        => 'required|string',
            'email'            => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix|unique:admins,email',
            'status_id'        => 'required|integer|in:1,2',
            'avatar'           => 'array|nullable',
            'avatar.content'   => 'string|nullable',
            'avatar.extension' => 'string|nullable',
            'avatar.mime'      => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'first_name.required'     => trans('validations/api/admin/general/admin/store.first_name.required'),
            'first_name.string'       => trans('validations/api/admin/general/admin/store.first_name.string'),
            'last_name.required'      => trans('validations/api/admin/general/admin/store.last_name.required'),
            'last_name.string'        => trans('validations/api/admin/general/admin/store.last_name.string'),
            'email.required'          => trans('validations/api/admin/general/admin/store.email.required'),
            'email.regex'             => trans('validations/api/admin/general/admin/store.email.regex'),
            'email.unique'            => trans('validations/api/admin/general/admin/store.unique.exists'),
            'status_id.required'      => trans('validations/api/admin/general/admin/store.status_id.required'),
            'status_id.integer'       => trans('validations/api/admin/general/admin/store.status_id.integer'),
            'status_id.in'            => trans('validations/api/admin/general/admin/store.status_id.in'),
            'avatar.array'            => trans('validations/api/admin/general/admin/store.avatar.array'),
            'avatar.content.string'   => trans('validations/api/admin/general/admin/store.avatar.content.string'),
            'avatar.extension.string' => trans('validations/api/admin/general/admin/store.avatar.extension.string'),
            'avatar.mime.string'      => trans('validations/api/admin/general/admin/store.avatar.mime.string')
        ];
    }
}
