<?php

namespace App\Http\Requests\Api\Guest\Catalog\Category;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetByCodeRequest
 *
 * @package App\Http\Requests\Api\Guest\Catalog\Category
 */
class GetByCodeRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'code' => 'string|required',
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'code.string'   => trans('validations/api/guest/catalog/category/getByCode.code.string'),
            'code.required' => trans('validations/api/guest/catalog/category/getByCode.code.required')
        ];
    }
}
