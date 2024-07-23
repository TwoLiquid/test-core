<?php

namespace App\Transformers\Api\Admin\General\Admin;

use App\Models\MySql\Role;
use App\Transformers\BaseTransformer;

/**
 * Class RoleTransformer
 *
 * @package App\Transformers\Api\Admin\General\Admin
 */
class RoleTransformer extends BaseTransformer
{
    /**
     * @param Role $role
     *
     * @return array
     */
    public function transform(Role $role) : array
    {
        return [
            'id'   => $role->id,
            'name' => $role->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'role';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'roles';
    }
}
