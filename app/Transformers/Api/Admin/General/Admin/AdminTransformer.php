<?php

namespace App\Transformers\Api\Admin\General\Admin;

use App\Models\MySql\Admin\Admin;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class AdminTransformer
 *
 * @package App\Transformers\Api\Admin\General\Admin
 */
class AdminTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $adminAvatars;

    /**
     * AdminTransformer constructor
     *
     * @param Collection|null $adminAvatars
     */
    public function __construct(
        Collection $adminAvatars = null
    )
    {
        /** @var Collection adminAvatars */
        $this->adminAvatars = $adminAvatars;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'avatar',
        'status',
        'auth_protection'
    ];

    /**
     * @param Admin $admin
     *
     * @return array
     */
    public function transform(Admin $admin) : array
    {
        return [
            'id'               => $admin->id,
            'full_id'          => $admin->full_id,
            'auth_id'          => $admin->auth_id,
            'last_name'        => $admin->last_name,
            'first_name'       => $admin->first_name,
            'email'            => $admin->email,
            'full_access'      => $admin->full_access,
            'initial_password' => $admin->initial_password
        ];
    }

    /**
     * @param Admin $admin
     *
     * @return Item|null
     */
    public function includeAvatar(Admin $admin) : ?Item
    {
        $adminAvatar = $this->adminAvatars?->filter(function ($item) use ($admin) {
            return $item->auth_id == $admin->auth_id;
        })->first();

        return $adminAvatar ? $this->item($adminAvatar, new AdminAvatarTransformer) : null;
    }

    /**
     * @param Admin $admin
     *
     * @return Item|null
     */
    public function includeStatus(Admin $admin) : ?Item
    {
        $status = $admin->getStatus();

        return $status ? $this->item($status, new AdminStatusTransformer) : null;
    }

    /**
     * @param Admin $admin
     *
     * @return Item|null
     */
    public function includeAuthProtection(Admin $admin) : ?Item
    {
        $authProtection = null;

        if ($admin->relationLoaded('authProtection')) {
            $authProtection = $admin->authProtection;
        }

        return $authProtection ? $this->item($authProtection, new AdminAuthProtectionTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'admin';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'admins';
    }
}
