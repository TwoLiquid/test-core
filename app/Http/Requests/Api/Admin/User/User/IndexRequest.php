<?php

namespace App\Http\Requests\Api\Admin\User\User;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\User\User
 */
class IndexRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'account_id',
        'buyer_id',
        'seller_id',
        'affiliate_id',
        'username',
        'first_name',
        'last_name',
        'country',
        'followers_count',
        'created_time',
        'created_date',
        'account_status',
        'buyer_status',
        'seller_status',
        'affiliate_status'
    ];

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'user_id'              => 'integer|exists:users,id|nullable',
            'username'             => 'string|nullable',
            'first_name'           => 'string|nullable',
            'last_name'            => 'string|nullable',
            'country_id'           => 'integer|exists:countries,id|nullable',
            'followers'            => 'integer|nullable',
            'date_from'            => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'              => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'statuses_ids'         => 'array|between:1,5|nullable',
            'user_balance_type_id' => 'integer|between:1,3|nullable',
            'sort_by'              => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'           => 'string|in:desc,asc|nullable',
            'paginated'            => 'boolean|nullable',
            'per_page'             => 'integer|nullable',
            'page'                 => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'user_id.integer'              => trans('validations/api/admin/user/user/index.user_id.integer'),
            'user_id.exists'               => trans('validations/api/admin/user/user/index.user_id.exists'),
            'username.string'              => trans('validations/api/admin/user/user/index.username.string'),
            'first_name.string'            => trans('validations/api/admin/user/user/index.first_name.string'),
            'last_name.string'             => trans('validations/api/admin/user/user/index.last_name.string'),
            'country_id.integer'           => trans('validations/api/admin/user/user/index.country_id.integer'),
            'country_id.exists'            => trans('validations/api/admin/user/user/index.country_id.exists'),
            'followers.integer'            => trans('validations/api/admin/user/user/index.followers.integer'),
            'date_from.string'             => trans('validations/api/admin/user/user/index.date_from.string'),
            'date_from.date_format'        => trans('validations/api/admin/user/user/index.date_from.date_format'),
            'date_to.string'               => trans('validations/api/admin/user/user/index.date_to.string'),
            'date_to.date_format'          => trans('validations/api/admin/user/user/index.date_to.date_format'),
            'statuses_ids.array'           => trans('validations/api/admin/user/user/index.statuses_ids.array'),
            'statuses_ids.*.integer'       => trans('validations/api/admin/user/user/index.statuses_ids.*.integer'),
            'statuses_ids.*.between'       => trans('validations/api/admin/user/user/index.statuses_ids.*.between'),
            'user_balance_type_id.integer' => trans('validations/api/admin/user/user/index.user_balance_type_id.integer'),
            'user_balance_type_id.between' => trans('validations/api/admin/user/user/index.user_balance_type_id.between'),
            'sort_by.string'               => trans('validations/api/admin/user/user/index.sort_by.string'),
            'sort_by.in'                   => trans('validations/api/admin/user/user/index.sort_by.in'),
            'sort_order.string'            => trans('validations/api/admin/user/user/index.sort_order.string'),
            'sort_order.in'                => trans('validations/api/admin/user/user/index.sort_order.in'),
            'paginated.boolean'            => trans('validations/api/admin/user/user/index.paginated.boolean'),
            'page.integer'                 => trans('validations/api/admin/user/user/index.page.integer'),
            'per_page.integer'             => trans('validations/api/admin/user/user/index.per_page.integer')
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
            'country_id'           => isset($params['country_id']) ? (int) $params['country_id'] : null,
            'followers'            => isset($params['followers']) ? (int) $params['followers'] : null,
            'statuses_ids'         => isset($params['statuses_ids']) ? explodeUrlIds($params['statuses_ids']) : null,
            'user_balance_type_id' => isset($params['user_balance_type_id']) ? (int) $params['user_balance_type_id'] : null,
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
