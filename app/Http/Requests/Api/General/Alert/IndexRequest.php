<?php

namespace App\Http\Requests\Api\General\Alert;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\General\Alert
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'type_id' => 'integer|nullable|between:1,9',
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'type_id.required' => trans('validations/api/general/alert/index.type_id.required'),
            'type_id.string'   => trans('validations/api/general/alert/index.type_id.string'),
            'type_id.between'  => trans('validations/api/general/alert/index.type_id.between')
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
