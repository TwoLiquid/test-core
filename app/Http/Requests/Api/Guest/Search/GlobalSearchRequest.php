<?php

namespace App\Http\Requests\Api\Guest\Search;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GlobalSearchRequest
 *
 * @package App\Http\Requests\Api\Guest\Search
 */
class GlobalSearchRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'search' => 'required|string',
            'limit'  => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'search.required' => trans('validations/api/guest/search/globalSearch.search.required'),
            'search.string'   => trans('validations/api/guest/search/globalSearch.search.string')
        ];
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
