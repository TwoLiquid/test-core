<?php

namespace App\Http\Requests\Api\Admin\General\IpBanList;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class StoreRequest
 *
 * @package App\Http\Requests\Api\Admin\General\IpBanList
 */
class StoreRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'ip_addresses' => 'required|string'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'ip_addresses.required' => trans('validations/api/admin/general/ipBanList/store.ip_addresses.required'),
            'ip_addresses.string'   => trans('validations/api/admin/general/ipBanList/store.ip_addresses.string')
        ];
    }
}
