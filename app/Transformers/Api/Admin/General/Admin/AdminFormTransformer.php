<?php

namespace App\Transformers\Api\Admin\General\Admin;

use App\Exceptions\DatabaseException;
use App\Lists\Admin\Status\AdminStatusList;
use App\Repositories\Role\RoleRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class AdminFormTransformer
 *
 * @package App\Transformers\Api\Admin\General\Admin
 */
class AdminFormTransformer extends BaseTransformer
{
    /**
     * @var RoleRepository
     */
    protected RoleRepository $roleRepository;

    /**
     * AdminFormTransformer constructor
     */
    public function __construct()
    {
        /** @var RoleRepository roleRepository */
        $this->roleRepository = new RoleRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'statuses',
        'roles'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     */
    public function includeStatuses() : ?Collection
    {
        $statuses = AdminStatusList::getItems();

        return $this->collection($statuses, new AdminStatusTransformer);
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeRoles() : ?Collection
    {
        $roles = $this->roleRepository->getAll();

        return $this->collection($roles, new RoleTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}
