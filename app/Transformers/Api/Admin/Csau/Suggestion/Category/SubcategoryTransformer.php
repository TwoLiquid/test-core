<?php

namespace App\Transformers\Api\Admin\Csau\Suggestion\Category;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;

/**
 * Class SubcategoryTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Suggestion\Category
 */
class SubcategoryTransformer extends BaseTransformer
{
    /**
     * @param Category $category
     *
     * @return array
     */
    public function transform(Category $category) : array
    {
        return [
            'id'      => $category->id,
            'name'    => $category->name,
            'code'    => $category->code,
            'visible' => $category->visible
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'subcategory';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'subcategories';
    }
}
