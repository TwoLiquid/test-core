<?php

namespace App\Transformers\Api\Guest\Catalog\Subcategory;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;
use App\Transformers\Api\Guest\Catalog\Activity\PaginatedActivityTransformer;
use App\Transformers\Api\Guest\Catalog\ActivityTag\ActivityTagListTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class CategoryTransformer
 *
 * @package App\Transformers\Api\Guest\Catalog\Subcategory
 */
class SubcategoryTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $categoryIcons;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activityImages;

    /**
     * @var int
     */
    protected int $perPage;

    /**
     * @var int
     */
    protected int $page;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'icon',
        'activities',
        'category',
        'tags'
    ];

    /**
     * SubcategoryTransformer constructor
     *
     * @param EloquentCollection|null $categoryIcons
     * @param EloquentCollection|null $activityImages
     * @param int $perPage
     * @param int $page
     */
    public function __construct(
        EloquentCollection $categoryIcons = null,
        EloquentCollection $activityImages = null,
        int $perPage = 18,
        int $page = 1
    )
    {
        /** @var EloquentCollection categoryIcons */
        $this->categoryIcons = $categoryIcons;

        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;

        /** @var int perPage */
        $this->perPage = $perPage;

        /** @var int page */
        $this->page = $page;
    }

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
     * @param Category $subcategory
     *
     * @return Item|null
     */
    public function includeCategory(Category $subcategory) : ?Item
    {
       return $subcategory->parent ?
           $this->item(
               $subcategory->parent,
               new CategoryTransformer(
                   $this->categoryIcons
               )
           ) : null;
    }

    /**
     * @param Category $subcategory
     *
     * @return Collection|null
     */
    public function includeTags(Category $subcategory) : ?Collection
    {
        $tags = null;

        if ($subcategory->relationLoaded('subcategoryTags')) {
            $tags = $subcategory->subcategoryTags;
        }

        return $tags ? $this->collection($tags, new ActivityTagListTransformer) : null;
    }

    /**
     * @param Category $category
     *
     * @return Collection|null
     */
    public function includeActivities(Category $category) : ?Item
    {
        $activities = null;

        if ($category->relationLoaded('activities')) {
            $activities = $category->activities;
        }

        return $activities ?
            $this->item(
                $activities,
                new PaginatedActivityTransformer(
                    $this->activityImages,
                    $this->perPage,
                    $this->page
                )
            ) : null;
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
