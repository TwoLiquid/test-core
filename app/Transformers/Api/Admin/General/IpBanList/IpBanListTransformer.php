<?php

namespace App\Transformers\Api\Admin\General\IpBanList;

use App\Models\MySql\IpBanList;
use App\Transformers\BaseTransformer;

/**
 * Class IpBanListTransformer
 *
 * @package App\Transformers\Api\Admin\General\IpBanList
 */
class IpBanListTransformer extends BaseTransformer
{
    /**
     * @param IpBanList $ipBanList
     * 
     * @return array
     */
    public function transform(IpBanList $ipBanList) : array
    {
        return [
            'id'         => $ipBanList->id,
            'ip_address' => $ipBanList->ip_address
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'ip_ban_list';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'ip_ban_list';
    }
}
