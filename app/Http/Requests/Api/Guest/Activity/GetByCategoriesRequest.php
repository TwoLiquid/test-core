<?php

namespace App\Http\Requests\Api\Guest\Activity;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetByCategoriesRequest
 *
 * @package App\Http\Requests\Api\Guest\Activity
 */
class GetByCategoriesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'categories_ids'   => 'array|nullable',
            'categories_ids.*' => 'required|integer|exists:categories,id',
            'name'             => 'string|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'categories_ids.array'      => trans('validations/api/guest/activity/getByCategories.categories_ids.array'),
            'categories_ids.*.required' => trans('validations/api/guest/activity/getByCategories.categories_ids.*.required'),
            'categories_ids.*.integer'  => trans('validations/api/guest/activity/getByCategories.categories_ids.*.integer'),
            'categories_ids.*.exists'   => trans('validations/api/guest/activity/getByCategories.categories_ids.*.exists'),
            'name.string'               => trans('validations/api/guest/activity/getByCategories.name.string')
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
            'categories_ids' => isset($params['categories_ids']) ? explodeUrlIds($params['categories_ids']) : null
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
