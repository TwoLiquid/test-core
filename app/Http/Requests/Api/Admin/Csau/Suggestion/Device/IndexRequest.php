<?php

namespace App\Http\Requests\Api\Admin\Csau\Suggestion\Device;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\Csau\Suggestion\Device
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'date_from'    => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'date_to'      => 'date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'username'     => 'string|nullable',
            'vybe_version' => 'integer|nullable',
            'vybe_title'   => 'string|nullable',
            'device_name'  => 'string|nullable',
            'paginated'    => 'boolean|nullable',
            'page'         => 'integer|nullable',
            'per_page'     => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'date_from.date_format' => trans('validations/api/admin/csau/suggestion/device/index.date_from.date_format'),
            'date_to.date_format'   => trans('validations/api/admin/csau/suggestion/device/index.date_to.date_format'),
            'username.string'       => trans('validations/api/admin/csau/suggestion/device/index.username.string'),
            'vybe_version.integer'  => trans('validations/api/admin/csau/suggestion/device/index.vybe_version.integer'),
            'vybe_title.string'     => trans('validations/api/admin/csau/suggestion/device/index.vybe_title.string'),
            'device_name.string'    => trans('validations/api/admin/csau/suggestion/device/index.device_name.string'),
            'paginated.boolean'     => trans('validations/api/admin/csau/suggestion/device/index.paginated.boolean'),
            'page.integer'          => trans('validations/api/admin/csau/suggestion/device/index.page.integer'),
            'per_page.integer'      => trans('validations/api/admin/csau/suggestion/device/index.per_page.integer'),
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
            'vybe_version' => isset($params['vybe_version']) ? (int) $params['vybe_version'] : null,
            'paginated'    => isset($params['paginated']) ? (bool) $params['paginated'] : null,
            'page'         => isset($params['page']) ? (int) $params['page'] : null,
            'per_page'     => isset($params['per_page']) ? (int) $params['per_page'] : null
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
