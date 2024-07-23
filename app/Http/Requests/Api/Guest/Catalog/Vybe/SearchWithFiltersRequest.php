<?php

namespace App\Http\Requests\Api\Guest\Catalog\Vybe;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class SearchWithFiltersRequest
 *
 * @package App\Http\Requests\Api\Guest\Catalog\Vybe
 */
class SearchWithFiltersRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'units_ids'               => 'array|nullable',
            'units_ids.*'             => 'required|integer|exists:units,id',
            'appearance_id'           => 'integer|between:1,3|nullable',
            'gender_id'               => 'integer|between:1,2|nullable',
            'city_place_id'           => 'string|nullable',
            'category_id'             => 'integer|exists:categories,id|nullable',
            'subcategory_id'          => 'integer|exists:categories,id|nullable',
            'personality_trait_ids'   => 'array|nullable',
            'personality_trait_ids.*' => 'required|integer',
            'activity_id'             => 'integer|exists:activities,id|nullable',
            'types_ids'               => 'array|nullable',
            'types_ids.*'             => 'required|integer|between:1,3',
            'devices_ids'             => 'array|nullable',
            'devices_ids.*'           => 'required|integer|exists:devices,id',
            'platforms_ids'           => 'array|nullable',
            'platforms_ids.*'         => 'required|integer|exists:platforms,id',
            'languages_ids'           => 'array|nullable',
            'languages_ids.*'         => 'required|integer',
            'tags_ids'                => 'array|nullable',
            'tags_ids.*'              => 'required|integer|exists:activity_tags,id',
            'date_min'                => 'date_format:Y-m-d|nullable',
            'date_max'                => 'date_format:Y-m-d|nullable',
            'price_min'               => 'numeric|nullable',
            'price_max'               => 'numeric|nullable',
            'has_all_tags'            => 'boolean|nullable',
            'sort'                    => 'integer|in:1,2,3,4|nullable',
            'page'                    => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'units_ids.array'                  => trans('validations/api/guest/catalog/vybe/searchWithFilters.units_ids.array'),
            'units_ids.*.required'             => trans('validations/api/guest/catalog/vybe/searchWithFilters.units_ids.*.required'),
            'units_ids.*.integer'              => trans('validations/api/guest/catalog/vybe/searchWithFilters.units_ids.*.integer'),
            'units_ids.*.exists'               => trans('validations/api/guest/catalog/vybe/searchWithFilters.units_ids.*.exists'),
            'appearance_id.integer'            => trans('validations/api/guest/catalog/vybe/searchWithFilters.appearance_id.integer'),
            'appearance_id.between'            => trans('validations/api/guest/catalog/vybe/searchWithFilters.appearance_id.between'),
            'gender_id.integer'                => trans('validations/api/guest/catalog/vybe/searchWithFilters.gender_id.integer'),
            'gender_id.between'                => trans('validations/api/guest/catalog/vybe/searchWithFilters.gender_id.between'),
            'city_place_id.string'             => trans('validations/api/guest/catalog/vybe/searchWithFilters.city_place_id.string'),
            'category_id.integer'              => trans('validations/api/guest/catalog/vybe/searchWithFilters.category_id.integer'),
            'category_id.exists'               => trans('validations/api/guest/catalog/vybe/searchWithFilters.category_id.exists'),
            'subcategory_id.integer'           => trans('validations/api/guest/catalog/vybe/searchWithFilters.subcategory_id.integer'),
            'subcategory_id.exists'            => trans('validations/api/guest/catalog/vybe/searchWithFilters.subcategory_id.exists'),
            'personality_traits_ids.array'     => trans('validations/api/guest/catalog/vybe/searchWithFilters.personality_trait_ids.array'),
            'personality_traits_ids.*.integer' => trans('validations/api/guest/catalog/vybe/searchWithFilters.personality_trait_ids.*.integer'),
            'activity_id.*.integer'            => trans('validations/api/guest/catalog/vybe/searchWithFilters.activity_id.integer'),
            'activity_id.*.exists'             => trans('validations/api/guest/catalog/vybe/searchWithFilters.activity_id.exists'),
            'types_ids.array'                  => trans('validations/api/guest/catalog/vybe/searchWithFilters.types_ids.integer'),
            'types_ids.*.between'              => trans('validations/api/guest/catalog/vybe/searchWithFilters.types_ids.*.between'),
            'types_ids.*.integer'              => trans('validations/api/guest/catalog/vybe/searchWithFilters.types_ids.*.integer'),
            'devices_ids.array'                => trans('validations/api/guest/catalog/vybe/searchWithFilters.devices_ids.array'),
            'devices_ids.*.required'           => trans('validations/api/guest/catalog/vybe/searchWithFilters.devices_ids.*.required'),
            'devices_ids.*.integer'            => trans('validations/api/guest/catalog/vybe/searchWithFilters.devices_ids.*.integer'),
            'devices_ids.*.exists'             => trans('validations/api/guest/catalog/vybe/searchWithFilters.devices_ids.*.exists'),
            'platforms_ids.array'              => trans('validations/api/guest/catalog/vybe/searchWithFilters.platforms_ids.array'),
            'platforms_ids.*.required'         => trans('validations/api/guest/catalog/vybe/searchWithFilters.platforms_ids.*.required'),
            'platforms_ids.*.integer'          => trans('validations/api/guest/catalog/vybe/searchWithFilters.platforms_ids.*.integer'),
            'platforms_ids.*.exists'           => trans('validations/api/guest/catalog/vybe/searchWithFilters.platforms_ids.*.exists'),
            'languages_ids.array'              => trans('validations/api/guest/catalog/vybe/searchWithFilters.languages_ids.array'),
            'languages_ids.*.integer'          => trans('validations/api/guest/catalog/vybe/searchWithFilters.languages_ids.*.integer'),
            'tags_ids.array'                   => trans('validations/api/guest/catalog/vybe/searchWithFilters.tags.array'),
            'tags_ids.*.integer'               => trans('validations/api/guest/catalog/vybe/searchWithFilters.tags.*.integer'),
            'tags_ids.*.exists'                => trans('validations/api/guest/catalog/vybe/searchWithFilters.tags.*.exists'),
            'date_min.date_format'             => trans('validations/api/guest/catalog/vybe/searchWithFilters.date_min.date_format'),
            'date_max.date_format'             => trans('validations/api/guest/catalog/vybe/searchWithFilters.date_max.date_format'),
            'price_min.numeric'                => trans('validations/api/guest/catalog/vybe/searchWithFilters.price_min.numeric'),
            'price_max.numeric'                => trans('validations/api/guest/catalog/vybe/searchWithFilters.price_max.numeric'),
            'has_all_tags.boolean'             => trans('validations/api/guest/catalog/vybe/searchWithFilters.has_all_tags.boolean'),
            'sort.integer'                     => trans('validations/api/guest/catalog/vybe/searchWithFilters.sort.integer'),
            'sort.in'                          => trans('validations/api/guest/catalog/vybe/searchWithFilters.sort.exists'),
            'page.integer'                     => trans('validations/api/guest/catalog/vybe/searchWithFilters.page.integer')
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
            'units_ids'              => isset($params['units_ids']) ? explodeUrlIds($params['units_ids']) : null,
            'appearance_id'          => isset($params['appearance_id']) ? (int) $params['appearance_id'] : null,
            'gender_id'              => isset($params['gender_id']) ? (int) $params['gender_id'] : null,
            'category_id'            => isset($params['category_id']) ? (int) $params['category_id'] : null,
            'subcategory_id'         => isset($params['subcategory_id']) ? (int) $params['subcategory_id'] : null,
            'personality_traits_ids' => isset($params['personality_traits_ids']) ? explodeUrlIds($params['personality_traits_ids']) : null,
            'activity_id'            => isset($params['activity_id']) ? (int) $params['activity_id'] : null,
            'types_ids'              => isset($params['types_ids']) ? explodeUrlIds($params['types_ids']) : null,
            'devices_ids'            => isset($params['devices_ids']) ? explodeUrlIds($params['devices_ids']) : null,
            'platforms_ids'          => isset($params['platforms_ids']) ? explodeUrlIds($params['platforms_ids']) : null,
            'languages_ids'          => isset($params['languages_ids']) ? explodeUrlIds($params['languages_ids']) : null,
            'tags_ids'               => isset($params['tags_ids']) ? explodeUrlIds($params['tags_ids']) : null,
            'date_min'               => isset($params['date_min']) ? (string) $params['date_min'] : null,
            'date_max'               => isset($params['date_max']) ? (string) $params['date_max'] : null,
            'price_min'              => isset($params['price_min']) ? (float) $params['price_min'] : null,
            'price_max'              => isset($params['price_max']) ? (float) $params['price_max'] : null,
            'has_all_tags'           => isset($params['has_all_tags']) ? (bool) $params['has_all_tags'] : null,
            'sort'                   => isset($params['sort']) ? (int) $params['sort'] : null,
            'page'                   => isset($params['page']) ? (int) $params['page'] : null,
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
