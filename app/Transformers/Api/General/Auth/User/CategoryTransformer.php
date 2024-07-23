<?php

namespace App\Transformers\Api\General\Auth\User;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;

/**
 * Class CategoryTransformer
 *
 * @package App\Transformers\Api\General\Auth\User
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
            'name'      => $category->name,
            'code'      => $category->code,
            'parent_id' => $category->parent_id
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
