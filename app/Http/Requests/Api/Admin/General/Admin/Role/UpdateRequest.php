<?php

namespace App\Http\Requests\Api\Admin\General\Admin\Role;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Api\Admin\General\Admin\Role
 */
class UpdateRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'                                      => 'required|string|unique:roles,name,' . $this->route('id'),
            'admins_ids'                                => 'array|nullable',
            'admins_ids.*'                              => 'required|integer|exists:admins,id',
            'departments'                               => 'required|array',
            'departments.*.department_code'             => 'required|string',
            'departments.*.page_code'                   => 'required|string',
            'departments.*.permissions'                 => 'required|array',
            'departments.*.permissions.*.permission_id' => 'required|integer|between:1,4',
            'departments.*.permissions.*.selected'      => 'required|boolean'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.required'                                      => trans('validations/api/admin/general/admin/role/update.name.required'),
            'name.string'                                        => trans('validations/api/admin/general/admin/role/update.name.string'),
            'name.unique'                                        => trans('validations/api/admin/general/admin/role/update.name.unique'),
            'admins_ids.array'                                   => trans('validations/api/admin/general/admin/role/update.admins_ids.array'),
            'admins_ids.*.required'                              => trans('validations/api/admin/general/admin/role/update.admins_ids.*.required'),
            'admins_ids.*.integer'                               => trans('validations/api/admin/general/admin/role/update.admins_ids.*.integer'),
            'admins_ids.*.exists'                                => trans('validations/api/admin/general/admin/role/update.admins_ids.*.exists'),
            'departments.required'                               => trans('validations/api/admin/general/admin/role/update.departments.required'),
            'departments.array'                                  => trans('validations/api/admin/general/admin/role/update.departments.array'),
            'departments.*.department_code.required'             => trans('validations/api/admin/general/admin/role/update.departments.*.department_code.required'),
            'departments.*.department_code.string'               => trans('validations/api/admin/general/admin/role/update.departments.*.department_code.string'),
            'departments.*.page_code.required'                   => trans('validations/api/admin/general/admin/role/update.departments.*.page_code.required'),
            'departments.*.page_code.string'                     => trans('validations/api/admin/general/admin/role/update.departments.*.page_code.string'),
            'departments.*.permissions.required'                 => trans('validations/api/admin/general/admin/role/update.departments.*.permissions.required'),
            'departments.*.permissions.array'                    => trans('validations/api/admin/general/admin/role/update.departments.*.permissions.array'),
            'departments.*.permissions.*.permission_id.required' => trans('validations/api/admin/general/admin/role/update.departments.*.permissions.*.permission_id.required'),
            'departments.*.permissions.*.permission_id.integer'  => trans('validations/api/admin/general/admin/role/update.departments.*.permissions.*.permission_id.integer'),
            'departments.*.permissions.*.permission_id.between'  => trans('validations/api/admin/general/admin/role/update.departments.*.permissions.*.permission_id.between'),
            'departments.*.permissions.*.selected.required'      => trans('validations/api/admin/general/admin/role/update.departments.*.permissions.*.selected.required'),
            'departments.*.permissions.*.selected.boolean'       => trans('validations/api/admin/general/admin/role/update.departments.*.permissions.*.selected.boolean')
        ];
    }
}
