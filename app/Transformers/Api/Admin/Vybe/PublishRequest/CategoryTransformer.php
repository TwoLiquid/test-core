<?php

namespace App\Transformers\Api\Admin\Vybe\PublishRequest;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;

/**
 * Class CategoryTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\PublishRequest
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
