<?php

namespace App\Http\Requests\Api\General\Dashboard\Vybe\Favorite;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetMoreFavoriteVybesRequest
 *
 * @package App\Http\Requests\Api\General\Dashboard\Vybe\Favorite
 */
class GetMoreFavoriteVybesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'type_id'  => 'required|integer|between:1,3',
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
            'type_id.required' => trans('validations/api/general/dashboard/vybe/favorite/getMoreFavoriteVybes.type_id.required'),
            'type_id.integer'  => trans('validations/api/general/dashboard/vybe/favorite/getMoreFavoriteVybes.type_id.integer'),
            'type_id.between'  => trans('validations/api/general/dashboard/vybe/favorite/getMoreFavoriteVybes.type_id.between'),
            'per_page.integer' => trans('validations/api/general/dashboard/vybe/favorite/getMoreFavoriteVybes.per_page.integer'),
            'page.integer'     => trans('validations/api/general/dashboard/vybe/favorite/getMoreFavoriteVybes.page.integer')
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
            'type_id'  => isset($params['type_id']) ? (int) $params['type_id'] : null,
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
