<?php

namespace App\Http\Requests\Api\Admin\Csau\Suggestion\Category;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Suggestion\Category
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'date_from'           => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'             => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'username'            => 'string|nullable',
            'vybe_version'        => 'integer|nullable',
            'vybe_title'          => 'string|nullable',
            'categories_ids'      => 'array|nullable',
            'categories_ids.*'    => 'required|integer|exists:categories,id',
            'subcategories_ids'   => 'array|nullable',
            'subcategories_ids.*' => 'required|integer|exists:categories,id',
            'activities_ids'      => 'array|nullable',
            'activities_ids.*'    => 'required|integer|exists:activities,id',
            'unit_types_ids'      => 'array|nullable',
            'unit_types_ids.*'    => 'required|integer|between:1,2',
            'units_ids'           => 'array|nullable',
            'units_ids.*'         => 'required|integer|exists:units,id',
            'paginated'           => 'boolean|nullable',
            'page'                => 'integer|nullable',
            'per_page'            => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'date_from.date_format'        => trans('validations/api/admin/csau/suggestion/category/index.date_from.date_format'),
            'date_to.date_format'          => trans('validations/api/admin/csau/suggestion/category/index.date_to.date_format'),
            'username.string'              => trans('validations/api/admin/csau/suggestion/category/index.username.string'),
            'vybe_version.integer'         => trans('validations/api/admin/csau/suggestion/category/index.search.string'),
            'vybe_title.string'            => trans('validations/api/admin/csau/suggestion/category/index.search.string'),
            'categories_ids.array'         => trans('validations/api/admin/csau/suggestion/category/index.categories_ids.array'),
            'categories_ids.*.required'    => trans('validations/api/admin/csau/suggestion/category/index.categories_ids.*.required'),
            'categories_ids.*.integer'     => trans('validations/api/admin/csau/suggestion/category/index.categories_ids.*.integer'),
            'categories_ids.*.exists'      => trans('validations/api/admin/csau/suggestion/category/index.categories_ids.*.exists'),
            'subcategories_ids.array'      => trans('validations/api/admin/csau/suggestion/category/index.subcategories_ids.array'),
            'subcategories_ids.*.required' => trans('validations/api/admin/csau/suggestion/category/index.subcategories_ids.*.required'),
            'subcategories_ids.*.integer'  => trans('validations/api/admin/csau/suggestion/category/index.subcategories_ids.*.integer'),
            'subcategories_ids.*.exists'   => trans('validations/api/admin/csau/suggestion/category/index.subcategories_ids.*.exists'),
            'activities_ids.array'         => trans('validations/api/admin/csau/suggestion/category/index.activities_ids.array'),
            'activities_ids.*.required'    => trans('validations/api/admin/csau/suggestion/category/index.activities_ids.*.required'),
            'activities_ids.*.integer'     => trans('validations/api/admin/csau/suggestion/category/index.activities_ids.*.integer'),
            'activities_ids.*.exists'      => trans('validations/api/admin/csau/suggestion/category/index.activities_ids.*.exists'),
            'unit_types_ids.array'         => trans('validations/api/admin/csau/suggestion/category/index.unit_types_ids.array'),
            'unit_types_ids.*.required'    => trans('validations/api/admin/csau/suggestion/category/index.unit_types_ids.*.required'),
            'unit_types_ids.*.integer'     => trans('validations/api/admin/csau/suggestion/category/index.unit_types_ids.*.integer'),
            'unit_types_ids.*.exists'      => trans('validations/api/admin/csau/suggestion/category/index.unit_types_ids.*.exists'),
            'units_ids.array'              => trans('validations/api/admin/csau/suggestion/category/index.units_ids.array'),
            'units_ids.*.required'         => trans('validations/api/admin/csau/suggestion/category/index.units_ids.*.required'),
            'units_ids.*.integer'          => trans('validations/api/admin/csau/suggestion/category/index.units_ids.*.integer'),
            'units_ids.*.exists'           => trans('validations/api/admin/csau/suggestion/category/index.units_ids.*.exists'),
            'paginated.boolean'            => trans('validations/api/admin/csau/suggestion/category/index.paginated.boolean'),
            'page.integer'                 => trans('validations/api/admin/csau/suggestion/category/index.page.integer'),
            'per_page.integer'             => trans('validations/api/admin/csau/suggestion/category/index.per_page.integer')
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
            'categories_ids'    => isset($params['categories_ids']) ? explodeUrlIds($params['categories_ids']) : null,
            'subcategories_ids' => isset($params['subcategories_ids']) ? explodeUrlIds($params['subcategories_ids']) : null,
            'activities_ids'    => isset($params['activities_ids']) ? explodeUrlIds($params['activities_ids']) : null,
            'unit_types_ids'    => isset($params['unit_types_ids']) ? explodeUrlIds($params['unit_types_ids']) : null,
            'units_ids'         => isset($params['units_ids']) ? explodeUrlIds($params['units_ids']) : null,
            'vybe_version'      => isset($params['vybe_version']) ? (int) $params['vybe_version'] : null,
            'paginated'         => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'page'              => isset($params['page']) ? (int) $params['page'] : null,
            'per_page'          => isset($params['per_page']) ? (int) $params['per_page'] : null
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
