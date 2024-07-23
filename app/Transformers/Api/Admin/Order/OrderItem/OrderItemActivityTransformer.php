<?php

namespace App\Transformers\Api\Admin\Order\OrderItem;

use App\Models\MySql\Activity\Activity;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class OrderItemActivityTransformer
 *
 * @package App\Transformers\Api\Admin\Order\OrderItem
 */
class OrderItemActivityTransformer extends BaseTransformer
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
            'code' => $activity->code,
            'name' => $activity->name
        ];
    }

    public function includeCategory(Activity $activity) : ?Item
    {
        if ($activity->relationLoaded('category')) {
            $category = $activity->category;

            return $category ? $this->item($category, new OrderItemCategoryTransformer) : null;
        }

        return null;
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
