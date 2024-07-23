<?php

namespace App\Http\Requests\Api\Admin\User\Setting;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\User\Setting
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'category' => 'required|string|in:buyer,seller'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'category.required' => trans('validations/api/admin/user/setting/index.category.required'),
            'category.string'   => trans('validations/api/admin/user/setting/user/index.category.string'),
            'category.in'       => trans('validations/api/admin/user/setting/user/index.category.in'),
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
