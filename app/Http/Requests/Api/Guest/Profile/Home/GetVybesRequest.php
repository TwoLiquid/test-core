<?php

namespace App\Http\Requests\Api\Guest\Profile\Home;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetVybesRequest
 *
 * @package App\Http\Requests\Api\Guest\Profile\Home
 */
class GetVybesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'activity_id'      => 'integer|exists:activities,id|nullable',
            'vybe_sort_id'     => 'integer|between:1,3|nullable',
            'vybe_types_ids'   => 'array|nullable',
            'vybe_types_ids.*' => 'integer|between:1,3|nullable',
            'page'             => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'activity_id.integer'      => trans('validations/api/guest/profile/home/getVybes.activity_id.integer'),
            'activity_id.exists'       => trans('validations/api/guest/profile/home/getVybes.activity_id.exists'),
            'vybe_sort_id.integer'     => trans('validations/api/guest/profile/home/getVybes.vybe_sort_id.integer'),
            'vybe_sort_id.between'     => trans('validations/api/guest/profile/home/getVybes.vybe_sort_id.between'),
            'vybe_types_ids.array'     => trans('validations/api/guest/profile/home/getVybes.vybe_types_ids.array'),
            'vybe_types_ids.*.integer' => trans('validations/api/guest/profile/home/getVybes.vybe_types_ids.*.integer'),
            'vybe_types_ids.*.between' => trans('validations/api/guest/profile/home/getVybes.vybe_types_ids.*.between'),
            'page.integer'             => trans('validations/api/guest/profile/home/getVybes.page.*.integer')
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
            'activity_id'    => isset($params['activity_id']) ? (int) $params['activity_id'] : null,
            'vybe_sort_id'   => isset($params['vybe_sort_id']) ? (int) $params['vybe_sort_id'] : null,
            'vybe_types_ids' => isset($params['vybe_types_ids']) ? explodeUrlIds($params['vybe_types_ids']) : null,
            'page'           => isset($params['page']) ? (int) $params['page'] : null
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
