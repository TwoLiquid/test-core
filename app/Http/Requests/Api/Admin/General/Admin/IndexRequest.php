<?php

namespace App\Http\Requests\Api\Admin\General\Admin;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\General\Admin
 */
class IndexRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'name',
        'email',
        'role_id',
        'status_id'
    ];

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'name'        => 'string|nullable',
            'email'       => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,20}$/ix|nullable',
            'roles_ids'   => 'array|nullable',
            'roles_ids.*' => 'integer|exists:roles,id|nullable',
            'status_id'   => 'integer|in:1,2|nullable',
            'paginated'   => 'boolean|nullable',
            'per_page'    => 'integer|nullable',
            'page'        => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.string'         => trans('validations/api/admin/general/admin/index.name.string'),
            'email.regex'         => trans('validations/api/admin/general/admin/index.email.regex'),
            'roles_ids.array'     => trans('validations/api/admin/general/admin/index.roles_ids.array'),
            'roles_ids.*.integer' => trans('validations/api/admin/general/admin/index.roles_ids.*.integer'),
            'roles_ids.*.exists'  => trans('validations/api/admin/general/admin/index.roles_ids.*.exists'),
            'status_id.integer'   => trans('validations/api/admin/general/admin/index.status_id.integer'),
            'status_id.in'        => trans('validations/api/admin/general/admin/index.status_id.in'),
            'paginated.boolean'   => trans('validations/api/admin/general/admin/index.paginated.boolean'),
            'per_page.integer'    => trans('validations/api/admin/general/admin/index.per_page.integer'),
            'page.integer'        => trans('validations/api/admin/general/admin/index.page.integer')
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation() : void
    {
        $params = $this->all();

        $this->merge([
            'roles_ids' => isset($params['roles_ids']) ? explodeUrlIds($params['roles_ids']) : null,
            'status_id' => isset($params['status_id']) ? (int) $params['status_id'] : null,
            'paginated' => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'per_page'  => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'      => isset($params['page']) ? (int) $params['page'] : null
        ]);
    }

    /**
     * @param null $keys
     *
     * @return array
     */
    public function all($keys = null) : array
    {
        return array_merge(
            parent::all(),
            $this->route()->parameters()
        );
    }
}
