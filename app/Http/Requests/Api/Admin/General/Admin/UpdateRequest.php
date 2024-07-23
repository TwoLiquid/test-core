<?php

namespace App\Http\Requests\Api\Admin\General\Admin;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\General\Admin
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'first_name'       => 'required|string',
            'last_name'        => 'required|string',
            'email'            => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix|unique:admins,email,' . $this->route('id'),
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
            'first_name.required'     => trans('validations/api/admin/general/admin/update.first_name.required'),
            'first_name.string'       => trans('validations/api/admin/general/admin/update.first_name.string'),
            'last_name.required'      => trans('validations/api/admin/general/admin/update.last_name.required'),
            'last_name.string'        => trans('validations/api/admin/general/admin/update.last_name.string'),
            'email.required'          => trans('validations/api/admin/general/admin/update.email.required'),
            'email.regex'             => trans('validations/api/admin/general/admin/update.email.regex'),
            'email.unique'            => trans('validations/api/admin/general/admin/update.unique.exists'),
            'status_id.required'      => trans('validations/api/admin/general/admin/update.status_id.required'),
            'status_id.integer'       => trans('validations/api/admin/general/admin/update.status_id.integer'),
            'status_id.in'            => trans('validations/api/admin/general/admin/update.status_id.in'),
            'avatar.array'            => trans('validations/api/admin/general/admin/update.avatar.array'),
            'avatar.content.string'   => trans('validations/api/admin/general/admin/update.avatar.content.string'),
            'avatar.extension.string' => trans('validations/api/admin/general/admin/update.avatar.extension.string'),
            'avatar.mime.string'      => trans('validations/api/admin/general/admin/update.avatar.mime.string')
        ];
    }
}
