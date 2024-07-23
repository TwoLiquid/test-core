<?php

namespace App\Transformers\Api\Guest\Catalog\Subcategory;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class CategoryTransformer
 *
 * @package App\Transformers\Api\Guest\Catalog\Subcategory
 */
class CategoryTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $categoryIcons;

    /**
     * CategoryTransformer constructor
     *
     * @param EloquentCollection|null $categoryIcons
     */
    public function __construct(
        EloquentCollection $categoryIcons = null
    )
    {
        /** @var EloquentCollection categoryIcons */
        $this->categoryIcons = $categoryIcons;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'icon',
        'subcategories'
    ];

    /**
     * @param Category $category
     *
     * @return array
     */
    public function transform(Category $category) : array
    {
        return [
            'id'          => $category->id,
            'code'        => $category->code,
            'name'        => $category->name,
            'visible'     => $category->visible,
            'position'    => $category->position,
            'vybes_count' => $category->vybes_count ? $category->vybes_count : 0
        ];
    }

    /**
     * @param Category $category
     *
     * @return Item|null
     */
    public function includeIcon(Category $category) : ?Item
    {
        $categoryIcon = $this->categoryIcons?->filter(function ($item) use ($category) {
            return $item->category_id == $category->id;
        })->first();

        return $categoryIcon ? $this->item($categoryIcon, new CategoryIconTransformer) : null;
    }

    /**
     * @param Category $category
     *
     * @return Collection|null
     */
    public function includeSubcategories(Category $category) : ?Collection
    {
        $subcategories = null;

        if ($category->relationLoaded('subcategories')) {
            $subcategories = $category->subcategories;
        }

        return $subcategories ?
            $this->collection(
                $subcategories,
                new SubcategoryShortTransformer(
                    $this->categoryIcons
                )
            ) : null;
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
