<?php

namespace App\Transformers\Api\Admin\General\Admin\Role;

use App\Models\MySql\Admin\Admin;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class AdminTransformer
 *
 * @package App\Transformers\Api\Admin\General\Admin\Role
 */
class AdminTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $adminAvatar;

    /**
     * AdminTransformer constructor
     *
     * @param Collection|null $adminAvatar
     */
    public function __construct(
        Collection $adminAvatar = null
    )
    {
        /** @var Collection adminAvatar */
        $this->adminAvatar = $adminAvatar;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'avatar'
    ];

    /**
     * @param Admin $admin
     *
     * @return array
     */
    public function transform(Admin $admin) : array
    {
        return [
            'id'         => $admin->id,
            'full_id'    => $admin->full_id,
            'auth_id'    => $admin->auth_id,
            'last_name'  => $admin->last_name,
            'first_name' => $admin->first_name
        ];
    }

    /**
     * @param Admin $admin
     *
     * @return Item|null
     */
    public function includeAvatar(Admin $admin) : ?Item
    {
        $adminAvatar = $this->adminAvatar?->filter(function ($item) use ($admin) {
            return $item->auth_id == $admin->auth_id;
        })->first();

        return $adminAvatar ? $this->item($adminAvatar, new AdminAvatarTransformer) : null;
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
