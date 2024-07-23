<?php

namespace App\Http\Requests\Api\Admin\Csau\Category;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class UpdatePositionsRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Category
 */
class UpdatePositionsRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'categories_items'            => 'required|array',
            'categories_items.*.id'       => 'required|integer|exists:categories,id',
            'categories_items.*.position' => 'required|integer'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'categories_items.required'            => trans('validations/api/admin/csau/category/updatePositions.categories_items.required'),
            'categories_items.array'               => trans('validations/api/admin/csau/category/updatePositions.categories_items.array'),
            'categories_items.*.id.required'       => trans('validations/api/admin/csau/category/updatePositions.categories_items.*.id.required'),
            'categories_items.*.id.integer'        => trans('validations/api/admin/csau/category/updatePositions.categories_items.*.id.integer'),
            'categories_items.*.id.exists'         => trans('validations/api/admin/csau/category/updatePositions.categories_items.*.id.exists'),
            'categories_items.*.position.required' => trans('validations/api/admin/csau/category/updatePositions.categories_items.*.position.required'),
            'categories_items.*.position.integer'  => trans('validations/api/admin/csau/category/updatePositions.categories_items.*.position.integer')
        ];
    }
}
