<?php

namespace App\Http\Requests\Api\Admin\Vybe;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\Vybe
 */
class IndexRequest extends BaseRequest
{
    /**
     * @var array
     */
    protected array $sortFields = [
        'id',
        'category',
        'subcategory',
        'activity',
        'type',
        'user',
        'title',
//        'price',
//        'unit',
        'status'
    ];

    /**
     * @return array
    */
    public function rules() : array
    {
        return [
            'vybe_id'             => 'integer|nullable',
            'categories_ids'      => 'array|nullable',
            'categories_ids.*'    => 'required|integer|exists:categories,id',
            'subcategories_ids'   => 'array|nullable',
            'subcategories_ids.*' => 'required|integer|exists:categories,id',
            'activities_ids'      => 'array|nullable',
            'activities_ids.*'    => 'required|integer|exists:activities,id',
            'types_ids'           => 'array|nullable',
            'types_ids.*'         => 'required|integer|between:1,3',
            'users_ids'           => 'array|nullable',
            'users_ids.*'         => 'required|integer|exists:users,id',
            'vybe_title'          => 'string|nullable',
            'price'               => 'numeric|nullable',
            'units_ids'           => 'array|nullable',
            'units_ids.*'         => 'required|integer|exists:units,id',
            'statuses_ids'        => 'array|nullable',
            'statuses_ids.*'      => 'required|integer|between:1,5',
            'sort_by'             => 'string|in:' . implode(',', $this->sortFields) . '|nullable',
            'sort_order'          => 'string|in:desc,asc|nullable',
            'per_page'            => 'integer|nullable',
            'page'                => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'vybe_id.integer'              => trans('validations/api/admin/vybe/index.vybe_id.integer'),
            'categories_ids.array'         => trans('validations/api/admin/vybe/index.categories_ids.array'),
            'categories_ids.*.required'    => trans('validations/api/admin/vybe/index.categories_ids.required'),
            'categories_ids.*.integer'     => trans('validations/api/admin/vybe/index.categories_ids.integer'),
            'subcategories_ids.array'      => trans('validations/api/admin/vybe/index.subcategories_ids.array'),
            'subcategories_ids.*.required' => trans('validations/api/admin/vybe/index.subcategories_ids.required'),
            'subcategories_ids.*.integer'  => trans('validations/api/admin/vybe/index.subcategories_ids.integer'),
            'activities_ids.array'         => trans('validations/api/admin/vybe/index.activities_ids.array'),
            'activities_ids.*.required'    => trans('validations/api/admin/vybe/index.activities_ids.required'),
            'activities_ids.*.integer'     => trans('validations/api/admin/vybe/index.activities_ids.integer'),
            'types_ids.array'              => trans('validations/api/admin/vybe/index.types_ids.array'),
            'types_ids.*.required'         => trans('validations/api/admin/vybe/index.types_ids.required'),
            'types_ids.*.integer'          => trans('validations/api/admin/vybe/index.types_ids.integer'),
            'users_ids.array'              => trans('validations/api/admin/vybe/index.users_ids.array'),
            'users_ids.*.required'         => trans('validations/api/admin/vybe/index.users_ids.required'),
            'users_ids.*.integer'          => trans('validations/api/admin/vybe/index.users_ids.integer'),
            'vybe_title.string'            => trans('validations/api/admin/vybe/index.vybe_title.string'),
            'price.numeric'                => trans('validations/api/admin/vybe/index.price.numeric'),
            'units_ids.*.required'         => trans('validations/api/admin/vybe/index.units_ids.required'),
            'units_ids.*.integer'          => trans('validations/api/admin/vybe/index.units_ids.integer'),
            'statuses_ids.array'           => trans('validations/api/admin/vybe/index.statuses_ids.array'),
            'statuses_ids.*.required'      => trans('validations/api/admin/vybe/index.statuses_ids.required'),
            'statuses_ids.*.integer'       => trans('validations/api/admin/vybe/index.statuses_ids.integer'),
            'sort_by.in'                   => trans('validations/api/admin/vybe/index.sort_by.in'),
            'sort_order.string'            => trans('validations/api/admin/vybe/index.sort_order.string'),
            'sort_order.in'                => trans('validations/api/admin/vybe/index.sort_order.in'),
            'per_page.integer'             => trans('validations/api/admin/vybe/index.per_page.integer'),
            'page.integer'                 => trans('validations/api/admin/vybe/index.page.integer')
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
            'vybe_id'           => isset($params['vybe_id']) ? (int) $params['vybe_id'] : null,
            'categories_ids'    => isset($params['categories_ids']) ? explodeUrlIds($params['categories_ids']) : null,
            'subcategories_ids' => isset($params['subcategories_ids']) ? explodeUrlIds($params['subcategories_ids']) : null,
            'activities_ids'    => isset($params['activities_ids']) ? explodeUrlIds($params['activities_ids']) : null,
            'types_ids'         => isset($params['types_ids']) ? explodeUrlIds($params['types_ids']) : null,
            'users_ids'         => isset($params['users_ids']) ? explodeUrlIds($params['users_ids']) : null,
            'price'             => isset($params['price']) ? (float) $params['price'] : null,
            'units_ids'         => isset($params['units_ids']) ? explodeUrlIds($params['units_ids']) : null,
            'statuses_ids'      => isset($params['statuses_ids']) ? explodeUrlIds($params['statuses_ids']) : null,
            'paginated'         => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'per_page'          => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'              => isset($params['page']) ? (int) $params['page'] : null
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