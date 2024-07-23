<?php

namespace App\Transformers\Api\Guest\Profile\Home;

use App\Models\MySql\Media\UserBackground;
use App\Transformers\BaseTransformer;

/**
 * Class UserBackgroundTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Home
 */
class UserBackgroundTransformer extends BaseTransformer
{
    /**
     * @param UserBackground $userBackground
     *
     * @return array
     */
    public function transform(UserBackground $userBackground) : array
    {
        return [
            'id'       => $userBackground->id,
            'url'      => $userBackground->url,
            'url_min'  => $userBackground->url_min,
            'mime'     => $userBackground->mime,
            'declined' => $userBackground->declined
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_background';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_backgrounds';
    }
}
