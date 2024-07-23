<?php

namespace App\Transformers\Api\Admin\Vybe\Form;

use App\Models\MySql\Media\CategoryIcon;
use App\Transformers\BaseTransformer;

/**
 * Class CategoryIconTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Form
 */
class CategoryIconTransformer extends BaseTransformer
{
    /**
     * @param CategoryIcon $categoryIcon
     *
     * @return array
     */
    public function transform(CategoryIcon $categoryIcon) : array
    {
        return [
            'id'   => $categoryIcon->id,
            'url'  => $categoryIcon->url,
            'mime' => $categoryIcon->mime
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'category_icon';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'category_icons';
    }
}
