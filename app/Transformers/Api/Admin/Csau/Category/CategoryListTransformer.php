<?php

namespace App\Transformers\Api\Admin\Csau\Category;

use App\Models\MySql\Category;
use App\Services\Activity\ActivityService;
use App\Services\Vybe\VybeService;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class CategoryListTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Category
 */
class CategoryListTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $categoryIcons;

    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * CategoryListTransformer constructor
     *
     * @param Collection|null $categoryIcons
     */
    public function __construct(
        Collection $categoryIcons = null
    )
    {
        /** @var Collection categoryIcons */
        $this->categoryIcons = $categoryIcons;

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
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
            'id'             => $category->id,
            'code'           => $category->code,
            'name'           => $category->getTranslations('name'),
            'visible'        => $category->visible,
            'position'       => $category->position,
            'activity_count' => $this->activityService->getCountByCategory($category),
            'vybes_count'    => $this->vybeService->getCountByCategory($category)
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
