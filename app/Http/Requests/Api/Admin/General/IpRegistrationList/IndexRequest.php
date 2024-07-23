<?php

namespace App\Http\Requests\Api\Admin\General\IpRegistrationList;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class IndexRequest
 *
 * @package App\Http\Requests\Api\Admin\General\IpRegistrationList
 */
class IndexRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'registration_date_from' => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'registration_date_to'   => 'string|date_format:Y-m-d\TH:i:s.v\Z|nullable',
            'ip_address'             => 'string|ip|nullable',
            'username'               => 'string|nullable',
            'name'                   => 'string|nullable',
            'statuses_ids'           => 'array|nullable',
            'statuses_ids.*'         => 'required|integer',
            'location'               => 'string|nullable',
            'vpn'                    => 'boolean|nullable',
            'duplicates'             => 'string|nullable',
            'per_page'               => 'integer|nullable',
            'page'                   => 'integer|nullable'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'registration_date_from.string'      => trans('validations/api/admin/general/ipRegistrationList/index.registration_date_from.string'),
            'registration_date_from.date_format' => trans('validations/api/admin/general/ipRegistrationList/index.registration_date_from.date_format'),
            'registration_date_to.string'        => trans('validations/api/admin/general/ipRegistrationList/index.registration_date_to.string'),
            'registration_date_to.date_format'   => trans('validations/api/admin/general/ipRegistrationList/index.registration_date_to.date_format'),
            'ip_address.ip'                      => trans('validations/api/admin/general/ipRegistrationList/index.ip_address.ip'),
            'username.string'                    => trans('validations/api/admin/general/ipRegistrationList/index.username.string'),
            'name.string'                        => trans('validations/api/admin/general/ipRegistrationList/index.name.string'),
            'statuses_ids.array'                 => trans('validations/api/admin/general/ipRegistrationList/index.statuses_ids.array'),
            'statuses_ids.*.integer'             => trans('validations/api/admin/general/ipRegistrationList/index.statuses_ids.*.integer'),
            'location.string'                    => trans('validations/api/admin/general/ipRegistrationList/index.location.string'),
            'vpn.boolean'                        => trans('validations/api/admin/general/ipRegistrationList/index.vpn.string'),
            'duplicates.string'                  => trans('validations/api/admin/general/ipRegistrationList/index.duplicates.string'),
            'page.integer'                       => trans('validations/api/admin/general/ipRegistrationList/index.page.integer'),
            'per_page.integer'                   => trans('validations/api/admin/general/ipRegistrationList/index.per_page.integer')
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
            'statuses_ids' => isset($params['statuses_ids']) ? explodeUrlIds($params['statuses_ids']) : null,
            'vpn'          => isset($params['vpn']) ? (bool) $params['vpn'] : null,
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
