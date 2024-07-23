<?php

namespace App\Transformers\Api\Admin\Vybe\VybeList;

use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Models\MySql\Vybe\Vybe;
use App\Transformers\Api\Admin\Vybe\VybeList\AppearanceCase\AppearanceCaseTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class VybeListTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\VybeList
 */
class VybeListTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'category',
        'subcategory',
        'activity',
        'type',
        'user',
        'appearance_cases',
        'status'
    ];

    /**
     * @param Vybe $vybe
     *
     * @return array
     */
    public function transform(Vybe $vybe) : array
    {
        return [
            'id'    => $vybe->id,
            'title' => $vybe->title
        ];
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeCategory(Vybe $vybe) : ?Item
    {
        $category = null;

        if ($vybe->relationLoaded('activity')) {
            $activity = $vybe->activity;

            if ($activity) {
                if ($activity->relationLoaded('category')) {
                    $category = $activity->category;

                    if ($category->relationLoaded('parent')) {
                        $parentCategory = $category->parent;

                        if ($parentCategory) {
                            $category = $parentCategory;
                        }
                    }
                }
            }
        }

        return $category ? $this->item($category, new CategoryTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeSubcategory(Vybe $vybe) : ?Item
    {
        $subcategory = null;

        if ($vybe->relationLoaded('activity')) {
            $activity = $vybe->activity;

            if ($activity) {
                if ($activity->relationLoaded('category')) {
                    $category = $activity->category;

                    if ($category->relationLoaded('parent')) {
                        $parentCategory = $category->parent;

                        if ($parentCategory) {
                            $subcategory = $activity->category;
                        }
                    }
                }
            }
        }

        return $subcategory ? $this->item($subcategory, new CategoryTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeActivity(Vybe $vybe) : ?Item
    {
        $activity = null;

        if ($vybe->relationLoaded('activity')) {
            $activity = $vybe->activity;
        }

        return $activity ? $this->item($activity, new ActivityTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeType(Vybe $vybe) : ?Item
    {
        $vybeType = VybeTypeList::getItem(
            $vybe->type_id
        );

        return $vybeType ? $this->item($vybeType, new VybeTypeTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeUser(Vybe $vybe) : ?Item
    {
        $user = null;

        if ($vybe->relationLoaded('user')) {
            $user = $vybe->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection|null
     */
    public function includeAppearanceCases(Vybe $vybe) : ?Collection
    {
        $appearanceCases = null;

        if ($vybe->relationLoaded('appearanceCases')) {
            $appearanceCases = $vybe->appearanceCases;
        }

        return $appearanceCases ? $this->collection($appearanceCases, new AppearanceCaseTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeStatus(Vybe $vybe) : ?Item
    {
        $vybeStatus = VybeStatusList::getItem(
            $vybe->status_id
        );

        return $vybeStatus ? $this->item($vybeStatus, new VybeStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'data';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybes';
    }
}
