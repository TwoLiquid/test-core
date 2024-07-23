<?php

namespace App\Transformers\Api\Guest\Catalog\Category;

use App\Models\MySql\Category;
use App\Services\Vybe\VybeService;
use App\Transformers\BaseTransformer;
use App\Transformers\Api\Guest\Catalog\Subcategory\SubcategoryShortTransformer;
use App\Transformers\Api\Guest\Catalog\ActivityTag\ActivityTagListTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class CategoryTransformer
 *
 * @package App\Transformers\Api\Guest\Catalog\Category
 */
class CategoryTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $categoryIcons;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

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

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'icon',
        'subcategories',
        'tags'
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
            'vybes_count' => $this->vybeService->countCategoryVybes($category)
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
     * @param Category $category
     *
     * @return Collection|null
     */
    public function includeTags(Category $category) : ?Collection
    {
        $tags = null;

        if ($category->relationLoaded('categoryTags')) {
            $tags = $category->categoryTags;
        }

        return $tags ? $this->collection($tags, new ActivityTagListTransformer) : null;
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
