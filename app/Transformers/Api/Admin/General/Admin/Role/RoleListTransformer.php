<?php

namespace App\Transformers\Api\Admin\General\Admin\Role;

use App\Models\MySql\Role;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class RoleListTransformer
 *
 * @package App\Transformers\Api\Admin\General\Admin\Role
 */
class RoleListTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $adminAvatars;

    /**
     * RoleListTransformer constructor
     *
     * @param EloquentCollection|null $adminAvatars
     */
    public function __construct(
        EloquentCollection $adminAvatars = null
    )
    {
        /** @var EloquentCollection adminAvatars */
        $this->adminAvatars = $adminAvatars;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'admins'
    ];

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
     * @param Role $role
     *
     * @return Collection|null
     */
    public function includeAdmins(Role $role) : ?Collection
    {
        $admins = null;

        if ($role->relationLoaded('admins')) {
            $admins = $role->admins;
        }

        return $admins ?
            $this->collection(
                $admins,
                new AdminTransformer(
                    $this->adminAvatars
                )
            ) : null;
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
