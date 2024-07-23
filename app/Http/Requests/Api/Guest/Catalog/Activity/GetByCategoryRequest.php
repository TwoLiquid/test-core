<?php

namespace App\Http\Requests\Api\Guest\Catalog\Activity;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetByCountryRequest
 *
 * @package App\Http\Requests\Api\Guest\Catalog\Activity
 */
class GetByCategoryRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'paginated' => 'boolean|nullable',
            'page'      => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'paginated.boolean' => trans('validations/api/guest/catalog/activity/getByCategory.paginated.boolean'),
            'page.integer'      => trans('validations/api/guest/catalog/activity/getByCategory.page.integer')
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
            'paginated' => isset($params['paginated']) ? (bool) $params['paginated'] : null,
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
