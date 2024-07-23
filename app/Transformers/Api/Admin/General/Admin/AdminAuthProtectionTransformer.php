<?php

namespace App\Transformers\Api\Admin\General\Admin;

use App\Models\MySql\Admin\AdminAuthProtection;
use App\Transformers\BaseTransformer;

/**
 * Class AdminAuthProtectionTransformer
 *
 * @package App\Transformers\Api\Admin\General\Admin
 */
class AdminAuthProtectionTransformer extends BaseTransformer
{
    /**
     * @param AdminAuthProtection $adminAuthProtection
     *
     * @return array
     */
    public function transform(AdminAuthProtection $adminAuthProtection) : array
    {
        return [
            'id'       => $adminAuthProtection->id,
            'added_at' => $adminAuthProtection->added_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'auth_protection';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'auth_protections';
    }
}
