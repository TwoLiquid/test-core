<?php

namespace App\Transformers\Api\Admin\Csau\ActivityTag;

use App\Models\MySql\Activity\ActivityTag;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class ActivityTagListTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\ActivityTag
 */
class ActivityTagListTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $categoryIcons;

    /**
     * ActivityTagListTransformer constructor
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
        'category',
        'subcategory',
    ];

    /**
     * @param ActivityTag $activityTag
     *
     * @return array
     */
    public function transform(ActivityTag $activityTag) : array
    {
        return [
            'id'                     => $activityTag->id,
            'name'                   => $activityTag->getTranslations('name'),
            'code'                   => $activityTag->code,
            'visible_in_category'    => $activityTag->visible_in_category,
            'visible_in_subcategory' => $activityTag->visible_in_subcategory,
            'activities_count'       => $activityTag->activities_count
        ];
    }

    /**
     * @param ActivityTag $activityTag
     *
     * @return Item|null
     */
    public function includeCategory(ActivityTag $activityTag) : ?Item
    {
        $category = null;

        if ($activityTag->relationLoaded('category')) {
            $category = $activityTag->category;
        }

        return $category ?
            $this->item(
                $category,
                new CategoryTransformer(
                    $this->categoryIcons
                )
            ) : null;
    }

    /**
     * @param ActivityTag $activityTag
     *
     * @return Item|null
     */
    public function includeSubcategory(ActivityTag $activityTag) : ?Item
    {
        $subcategory = null;

        if ($activityTag->relationLoaded('subcategory')) {
            $subcategory = $activityTag->subcategory;
        }

        return $subcategory ?
            $this->item(
                $subcategory,
                new CategoryTransformer(
                    $this->categoryIcons
                )
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'activity_tag';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'activity_tags';
    }
}
