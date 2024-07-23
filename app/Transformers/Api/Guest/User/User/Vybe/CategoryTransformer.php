<?php

namespace App\Transformers\Api\Guest\User\User\Vybe;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;

/**
 * Class CategoryTransformer
 *
 * @package App\Transformers\Api\Guest\User\User\Vybe
 */
class CategoryTransformer extends BaseTransformer
{
    /**
     * @param Category $category
     *
     * @return array
     */
    public function transform(Category $category) : array
    {
        return [
            'id'        => $category->id,
            'parent_id' => $category->parent_id,
            'name'      => $category->name,
            'code'      => $category->code,
            'visible'   => $category->visible,
            'position'  => $category->position
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'category';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'categories';
    }
}
