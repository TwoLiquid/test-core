<?php

namespace App\Transformers\Api\General\Navbar;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class CategoryTransformer
 *
 * @package App\Transformers\Api\General\Navbar
 */
class CategoryTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $categoryIcons;

    /**
     * CategoryTransformer constructor
     *
     * @param Collection|null $categoryIcons
     */
    public function __construct(
        Collection $categoryIcons = null
    )
    {
        /** @var Collection categoryIcons */
        $this->categoryIcons = $categoryIcons;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'icon'
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
