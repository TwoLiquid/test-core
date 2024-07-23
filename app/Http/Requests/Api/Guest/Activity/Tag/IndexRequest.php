<?php

namespace App\Http\Requests\Api\Guest\Activity\Tag;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Guest\Activity\Tag
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'category_id'    => 'integer|exists:categories,id|nullable',
            'subcategory_id' => 'integer|exists:categories,id|nullable',
            'search'         => 'string|nullable',
            'paginated'      => 'boolean|nullable',
            'page'           => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'category_id.integer'    => trans('validations/api/guest/activityTag/index.category_id.integer'),
            'category_id.exists'     => trans('validations/api/guest/activityTag/index.category_id.exists'),
            'subcategory_id.integer' => trans('validations/api/guest/activityTag/index.subcategory_id.integer'),
            'subcategory_id.exists'  => trans('validations/api/guest/activityTag/index.subcategory_id.exists'),
            'search.string'          => trans('validations/api/guest/activityTag/index.search.string'),
            'paginated.boolean'      => trans('validations/api/guest/activityTag/index.paginated.boolean'),
            'page.integer'           => trans('validations/api/guest/activityTag/index.page.integer')
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
            'category_id'    => isset($params['category_id']) ? (int) $params['category_id'] : null,
            'subcategory_id' => isset($params['subcategory_id']) ? (int) $params['subcategory_id'] : null,
            'paginated'      => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'page'           => isset($params['page']) ? (int) $params['page'] : null
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
