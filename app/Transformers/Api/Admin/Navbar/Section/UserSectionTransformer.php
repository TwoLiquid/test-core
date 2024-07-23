<?php

namespace App\Transformers\Api\Admin\Navbar\Section;

use App\Transformers\BaseTransformer;

/**
 * Class UserSectionTransformer
 *
 * @package App\Transformers\Api\Admin\Navbar\Section
 */
class UserSectionTransformer extends BaseTransformer
{
    /**
     * @param array $users
     *
     * @return array
     */
    public function transform(array $users) : array
    {
        return $users;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'users';
    }
}
