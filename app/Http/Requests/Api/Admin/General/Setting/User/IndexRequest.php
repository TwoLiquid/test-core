<?php

namespace App\Http\Requests\Api\Admin\General\Setting\User;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\General\Setting\User
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
            'category.required' => trans('validations/api/admin/general/setting/user/index.category.required'),
            'category.string'   => trans('validations/api/admin/general/setting/user/index.category.string'),
            'category.in'       => trans('validations/api/admin/general/setting/user/index.category.in'),
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
