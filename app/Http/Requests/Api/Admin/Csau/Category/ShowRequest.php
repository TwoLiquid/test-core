<?php

namespace App\Http\Requests\Api\Admin\Csau\Category;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class ShowRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category
 */
class ShowRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'page'      => 'integer|nullable',
            'per_page'  => 'integer|nullable',
            'paginated' => 'boolean|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'page.integer'      => trans('validations/api/admin/csau/category/show.page.integer'),
            'per_page.integer'  => trans('validations/api/admin/csau/category/show.per_page.integer'),
            'paginated.boolean' => trans('validations/api/admin/csau/category/show.paginated.boolean')
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
            'page'      => isset($params['page']) ? (int) $params['page'] : null,
            'per_page'  => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'paginated' => isset($params['paginated']) ? (bool) $params['paginated'] : null
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
