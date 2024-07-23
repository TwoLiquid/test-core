<?php

namespace App\Http\Requests\Api\Admin\Request\User\DeactivationRequest;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\Request\User\DeactivationRequest
 */
class IndexRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'id',
        'created_at',
        'waiting',
        'request_status'
    ];

    /**
     * @return array
    */
    public function rules() : array
    {
        return [
            'request_id'             => 'string|nullable',
            'user_id'                => 'integer|nullable',
            'date_from'              => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'                => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'username'               => 'string|nullable',
            'sales'                  => 'integer|nullable',
            'languages_ids'          => 'array|nullable',
            'languages_ids.*'        => 'required|integer',
            'account_statuses_ids'   => 'array|nullable',
            'account_statuses_ids.*' => 'required|integer',
            'request_statuses_ids'   => 'array|nullable',
            'request_statuses_ids.*' => 'required|integer',
            'admin'                  => 'string|nullable',
            'sort_by'                => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'             => 'string|in:desc,asc|nullable',
            'paginated'              => 'boolean|nullable',
            'per_page'               => 'integer|nullable',
            'page'                   => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'request_id.required'             => trans('validations/api/admin/request/user/deactivationRequest/index.request_id.required'),
            'request_id.string'               => trans('validations/api/admin/request/user/deactivationRequest/index.request_id.string'),
            'user_id.required'                => trans('validations/api/admin/request/user/deactivationRequest/index.user_id.required'),
            'user_id.integer'                 => trans('validations/api/admin/request/user/deactivationRequest/index.user_id.integer'),
            'date_from.string'                => trans('validations/api/admin/request/user/deactivationRequest/index.date_from.string'),
            'date_from.date_format'           => trans('validations/api/admin/request/user/deactivationRequest/index.date_from.date_format'),
            'date_to.string'                  => trans('validations/api/admin/request/user/deactivationRequest/index.date_to.string'),
            'date_to.date_format'             => trans('validations/api/admin/request/user/deactivationRequest/index.date_to.date_format'),
            'username.string'                 => trans('validations/api/admin/request/user/deactivationRequest/index.username.string'),
            'sales.integer'                   => trans('validations/api/admin/request/user/deactivationRequest/index.sales.integer'),
            'languages_ids.array'             => trans('validations/api/admin/request/user/deactivationRequest/index.languages_ids.*.array'),
            'languages_ids.*.required'        => trans('validations/api/admin/request/user/deactivationRequest/index.languages_ids.*.required'),
            'languages_ids.*.integer'         => trans('validations/api/admin/request/user/deactivationRequest/index.languages_ids.*.integer'),
            'account_statuses_ids.array'      => trans('validations/api/admin/request/user/deactivationRequest/index.account_statuses_ids.*.array'),
            'account_statuses_ids.*.required' => trans('validations/api/admin/request/user/deactivationRequest/index.account_statuses_ids.*.required'),
            'account_statuses_ids.*.integer'  => trans('validations/api/admin/request/user/deactivationRequest/index.account_statuses_ids.*.integer'),
            'request_statuses_ids.array'      => trans('validations/api/admin/request/user/deactivationRequest/index.request_statuses_ids.*.array'),
            'request_statuses_ids.*.required' => trans('validations/api/admin/request/user/deactivationRequest/index.request_statuses_ids.*.required'),
            'request_statuses_ids.*.integer'  => trans('validations/api/admin/request/user/deactivationRequest/index.request_statuses_ids.*.integer'),
            'admin.string'                    => trans('validations/api/admin/request/user/deactivationRequest/index.admin.string'),
            'sort_by.string'                  => trans('validations/api/admin/request/user/deactivationRequest/index.sort_by.string'),
            'sort_by.in'                      => trans('validations/api/admin/request/user/deactivationRequest/index.sort_by.in'),
            'sort_order.string'               => trans('validations/api/admin/request/user/deactivationRequest/index.sort_order.string'),
            'sort_order.in'                   => trans('validations/api/admin/request/user/deactivationRequest/index.sort_order.in'),
            'paginated.boolean'               => trans('validations/api/admin/request/user/deactivationRequest/index.paginated.boolean'),
            'per_page.integer'                => trans('validations/api/admin/request/user/deactivationRequest/index.per_page.integer'),
            'page.integer'                    => trans('validations/api/admin/request/user/deactivationRequest/index.page.integer')
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
            'user_id'              => isset($params['user_id']) ? (int) $params['user_id'] : null,
            'sales'                => isset($params['sales']) ? (int) $params['sales'] : null,
            'languages_ids'        => isset($params['languages_ids']) ? explodeUrlIds($params['languages_ids']) : null,
            'account_statuses_ids' => isset($params['account_statuses_ids']) ? explodeUrlIds($params['account_statuses_ids']) : null,
            'request_statuses_ids' => isset($params['request_statuses_ids']) ? explodeUrlIds($params['request_statuses_ids']) : null,
            'paginated'            => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'per_page'             => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'                 => isset($params['page']) ? (int) $params['page'] : null
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