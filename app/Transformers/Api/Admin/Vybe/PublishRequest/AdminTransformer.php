<?php

namespace App\Transformers\Api\Admin\Vybe\PublishRequest;

use App\Models\MySql\Admin\Admin;
use App\Transformers\BaseTransformer;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\PublishRequest
 */
class AdminTransformer extends BaseTransformer
{
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