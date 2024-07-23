<?php

namespace App\Transformers\Api\Admin\Order\OrderItem;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemCategoryTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem
 */
class OrderItemCategoryTransformer extends BaseTransformer
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
            'id'   => $category->id,
            'code' => $category->code,
            'name' => $category->name
        ];
    }

    /**
     * @param Category $category
     *
     * @return Item|null
     */
    public function includeParent(Category $category) : ?Item
    {
        $parent = null;
        if ($category->relationLoaded('parent')) {
            $parent = $category->parent;
        }

        return $parent ? $this->item($parent, new OrderItemCategoryTransformer) : null;
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
