<?php

namespace App\Transformers\Api\Admin\Csau\ActivityTag\Activity;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class CategoryTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\ActivityTag\Activity
 */
class CategoryTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'parent'
    ];

    /**
     * @param Category $category
     *
     * @return array
     */
    public function transform(Category $category) : array
    {
        return [
            'id'      => $category->id,
            'code'    => $category->code,
            'name'    => $category->name,
            'visible' => $category->visible
        ];
    }

    /**
     * @param Category $category
     *
     * @return Item|null
     */
    public function includeParent(Category $category) : ?Item
    {
        $parentCategory = null;

        if ($category->relationLoaded('parent')) {
            $parentCategory = $category->parent;
        }

        return $parentCategory ? $this->item($parentCategory, new CategoryTransformer) : null;
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
