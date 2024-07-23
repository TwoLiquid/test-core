<?php

namespace App\Http\Requests\Api\Guest\Catalog\Subcategory;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetByCodeRequest
 *
 * @package App\Http\Requests\Api\Guest\Catalog\Subcategory
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
            'code.string'   => trans('validations/api/guest/catalog/subcategory/getByCode.code.string'),
            'code.required' => trans('validations/api/guest/catalog/subcategory/getByCode.code.required')
        ];
    }
}