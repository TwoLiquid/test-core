<?php

namespace App\Transformers\Api\Admin\Csau\Category;

use App\Models\MySql\Category;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\CategoryIconRepository;
use App\Services\Activity\ActivityService;
use App\Services\Vybe\VybeService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class CategoryWithPaginationTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Category
 */
class CategoryWithPaginationTransformer extends BaseTransformer
{
    /**
     * @var ActivityImageRepository
     */
    protected ActivityImageRepository $activityImageRepository;

    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;

    /**
     * @var CategoryIconRepository
     */
    protected CategoryIconRepository $categoryIconRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

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
     * CategoryWithPaginationTransformer constructor
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
        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var CategoryIconRepository categoryIconRepository */
        $this->categoryIconRepository = new CategoryIconRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

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
     * @var array
     */
    protected array $defaultIncludes = [
        'icon',
        'subcategories',
        'activities',
        'category_tags',
        'subcategory_tags'
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
            'vybe_count'     => $this->vybeService->getCountByCategory($category)
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
     * @param Category $category
     *
     * @return Collection|null
     */
    public function includeCategoryTags(Category $category) : ?Collection
    {
        $tags = null;

        if ($category->relationLoaded('categoryTags')) {
            $tags = $category->categoryTags;
        }

        return $tags ? $this->collection($tags, new ActivityTagTransformer) : null;
    }

    /**
     * @param Category $category
     *
     * @return Collection|null
     */
    public function includeSubcategoryTags(Category $category) : ?Collection
    {
        $tags = null;

        if ($category->relationLoaded('subcategoryTags')) {
            $tags = $category->subcategoryTags;
        }

        return $tags ? $this->collection($tags, new ActivityTagTransformer) : null;
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
