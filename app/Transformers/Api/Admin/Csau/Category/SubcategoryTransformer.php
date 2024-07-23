<?php

namespace App\Transformers\Api\Admin\Csau\Category;

use App\Models\MySql\Category;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class SubcategoryTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Category
 */
class SubcategoryTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'activities'
    ];

    /**
     * @param Category $category
     *
     * @return array
     */
    public function transform(Category $category) : array
    {
        return [
            'id'               => $category->id,
            'parent_id'        => $category->parent_id,
            'name'             => $category->name,
            'code'             => $category->code,
            'visible'          => $category->visible,
            'position'         => $category->position,
            'activities_count' => $category->activities_count ? $category->activities_count : null,
            'vybes_count'      => $category->vybes_count ? $category->vybes_count : 0
        ];
    }

    /**
     * @param Category $category
     *
     * @return Collection|null
     */
    public function includeActivities(Category $category) : ?Collection
    {
        $activities = null;

        if ($category->relationLoaded('activities')) {
            $activities = $category->activities;
        }

        return $activities ? $this->collection($activities, new ActivityTransformer) : null;
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
