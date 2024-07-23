<?php

namespace App\Transformers\Api\Guest\Profile\Vybe;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Vybe\Vybe;
use App\Transformers\Api\Guest\Profile\Vybe\Vybe\CategoryTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class FilterActivityTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Vybe
 */
class FilterActivityTransformer extends BaseTransformer
{
    /**
     * @var Collection
     */
    protected Collection $vybes;

    /**
     * FilterActivityTransformer constructor
     *
     * @param Collection $vybes
     */
    public function __construct(
        Collection $vybes
    )
    {
        $this->vybes = $vybes;
    }

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
        $vybesCount = 0;

        /** @var Vybe $vybe */
        foreach ($this->vybes as $vybe) {
            if ($activity->id == $vybe->activity_id) {
                $vybesCount++;
            }
        }

        return [
            'id'          => $activity->id,
            'name'        => $activity->name,
            'code'        => $activity->code,
            'vybes_count' => $vybesCount
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
