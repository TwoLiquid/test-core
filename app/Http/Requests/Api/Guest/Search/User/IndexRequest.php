<?php

namespace App\Http\Requests\Api\Guest\Search\User;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Guest\Search\User
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'search'   => 'string|nullable',
            'per_page' => 'integer|nullable',
            'page'     => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'search.string'    => trans('validations/api/guest/search/user/index.search.string'),
            'per_page.integer' => trans('validations/api/guest/search/user/index.per_page.integer'),
            'page.integer'     => trans('validations/api/guest/search/user/index.page.integer')
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
            'per_page' => isset($params['per_page']) ? (int) $params['per_page'] : null,
            'page'     => isset($params['page']) ? (int) $params['page'] : null
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
