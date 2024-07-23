<?php

namespace App\Http\Requests\Api\Admin\Csau\Platform;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class GetActivitiesRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Platform
 */
class GetActivitiesRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'search'   => 'string|nullable',
            'page'     => 'integer|nullable',
            'per_page' => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'search.string'    => trans('validations/api/admin/csau/platform/getActivities.search.string'),
            'page.integer'     => trans('validations/api/admin/csau/platform/getActivities.page.integer'),
            'per_page.integer' => trans('validations/api/admin/csau/platform/getActivities.per_page.integer')
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
            'page'     => isset($params['page']) ? (int) $params['page'] : null,
            'per_page' => isset($params['per_page']) ? (int) $params['per_page'] : null
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