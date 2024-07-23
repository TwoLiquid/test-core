<?php

namespace App\Transformers\Api\Admin\Auth;

use App\Models\MySql\Admin\Admin;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class AdminTransformer
 *
 * @package App\Transformers\Api\Admin\Auth
 */
class AdminTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'status'
    ];

    /**
     * @param Admin $admin
     *
     * @return array
     */
    public function transform(Admin $admin) : array
    {
        return [
            'id'                   => $admin->id,
            'auth_id'              => $admin->auth_id,
            'last_name'            => $admin->last_name,
            'first_name'           => $admin->first_name,
            'email'                => $admin->email,
            'full_access'          => $admin->full_access,
            'initial_password'     => $admin->initial_password,
            'password_reset_token' => $admin->password_reset_token,
            'auth_protection'      => $admin->hasAuthProtection()
        ];
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
