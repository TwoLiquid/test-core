<?php

namespace App\Transformers\Api\Admin\User\Billing\VatNumberProof;

use App\Models\MySql\Admin\Admin;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class AdminTransformer
 *
 * @package App\Transformers\Api\Admin\User\Billing\VatNumberProof
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
            'auth_id'    => $admin->auth_id,
            'last_name'  => $admin->last_name,
            'first_name' => $admin->first_name
        ];
    }

    public function includeAvatar(Admin $admin) : ?Item
    {
        $adminAvatar = $this->adminAvatars?->filter(function ($item) use ($admin) {
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
