<?php

namespace App\Http\Requests\Api\Admin\General\IpBanList;

use App\Http\Requests\Api\BaseRequest;

/**
 * Class DestroyManyRequest
 *
 * @package App\Http\Requests\Api\Admin\General\IpBanList
 */
class DestroyManyRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'ip_ban_list_ids'   => 'required|array',
            'ip_ban_list_ids.*' => 'required|integer|exists:ip_ban_list,id'
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'ip_ban_list_ids.required'   => trans('validations/api/admin/general/ipBanList/destroyMany.ip_ban_list_ids.required'),
            'ip_ban_list_ids.array'      => trans('validations/api/admin/general/ipBanList/destroyMany.ip_ban_list_ids.array'),
            'ip_ban_list_ids.*.required' => trans('validations/api/admin/general/ipBanList/destroyMany.ip_ban_list_ids.required'),
            'ip_ban_list_ids.*.integer'  => trans('validations/api/admin/general/ipBanList/destroyMany.ip_ban_list_ids.integer'),
            'ip_ban_list_ids.*.exists'   => trans('validations/api/admin/general/ipBanList/destroyMany.ip_ban_list_ids.exists')
        ];
    }
}
