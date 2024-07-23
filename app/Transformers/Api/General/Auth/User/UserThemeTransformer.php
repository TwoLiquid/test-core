<?php

namespace App\Transformers\Api\General\Auth\User;

use App\Lists\User\Theme\UserThemeListItem;
use App\Transformers\BaseTransformer;

/**
 * Class UserThemeTransformer
 *
 * @package App\Transformers\Api\General\Auth\User
 */
class UserThemeTransformer extends BaseTransformer
{
    /**
     * @param UserThemeListItem $userThemeListItem
     *
     * @return array
     */
    public function transform(UserThemeListItem $userThemeListItem) : array
    {
        return [
            'id'   => $userThemeListItem->id,
            'code' => $userThemeListItem->code,
            'name' => $userThemeListItem->name
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_theme';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_themes';
    }
}
