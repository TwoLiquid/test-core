<?php

namespace App\Transformers\Api\Guest\Home\Category;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;
use App\Transformers\Api\Guest\Home\Vybe\VybeTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class CategoryTransformer
 *
 * @package App\Transformers\Api\Guest\Home\Category
 */
class CategoryTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $categoryIcons;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeVideos;

    /**
     * CategoryTransformer constructor
     *
     * @param EloquentCollection|null $categoryIcons
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     */
    public function __construct(
        EloquentCollection $categoryIcons = null,
        EloquentCollection $vybeImages = null,
        EloquentCollection $vybeVideos = null
    )
    {
        /** @var EloquentCollection categoryIcons */
        $this->categoryIcons = $categoryIcons;

        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'icon',
        'vybes',
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
            'id'       => $category->id,
            'name'     => $category->name,
            'code'     => $category->code,
            'visible'  => $category->visible,
            'position' => $category->position
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
    public function includeVybes(Category $category) : ?Collection
    {
        $vybes = null;

        if ($category->relationLoaded('vybes')) {
            $vybes = $category->vybes;
        }

        return $vybes ?
            $this->collection(
                $vybes,
                new VybeTransformer(
                    null,
                    $this->vybeImages,
                    $this->vybeVideos
                )
            ) : null;
    }

    /**
     * @param Category $category
     *
     * @return Item|null
     */
    public function includeParent(Category $category): ?Item
    {
        $parentCategory = null;

        if ($category->relationLoaded('parent')) {
            $parentCategory = $category->parent;
        }

        return $parentCategory ?
            $this->item(
                $parentCategory,
                new CategoryTransformer(
                    $this->categoryIcons,
                    $this->vybeImages,
                    $this->vybeVideos
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
