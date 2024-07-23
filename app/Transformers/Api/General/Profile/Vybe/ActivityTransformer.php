<?php

namespace App\Transformers\Api\General\Profile\Vybe;

use App\Models\MySql\Activity\Activity;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class ActivityTransformer
 *
 * @package App\Transformers\Api\General\Profile\Vybe
 */
class ActivityTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'category'
    ];

    /**
     * @param Activity $activity
     *
     * @return array
     */
    public function transform(Activity $activity) : array
    {
        return [
            'id'   => $activity->id,
            'name' => $activity->name,
            'code' => $activity->code
        ];
    }

    /**
     * @param Activity $activity
     *
     * @return Item|null
     */
    public function includeCategory(Activity $activity) : ?Item
    {
        $category = null;

        if ($activity->relationLoaded('category')) {
            $category = $activity->category;
        }

        return $category ? $this->item($category, new CategoryTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'activity';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'activities';
    }
}
