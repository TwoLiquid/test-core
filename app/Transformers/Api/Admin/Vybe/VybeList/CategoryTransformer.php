<?php

namespace App\Transformers\Api\Admin\Vybe\VybeList;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;

/**
 * Class CategoryTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\VybeList
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
            'id'   => $category->id,
            'name' => $category->name,
            'code' => $category->code
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