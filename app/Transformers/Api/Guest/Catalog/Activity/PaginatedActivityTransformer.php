<?php

namespace App\Transformers\Api\Guest\Catalog\Activity;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class PaginatedActivityTransformer
 *
 * @package App\Transformers\Api\Guest\Catalog\Category
 */
class PaginatedActivityTransformer extends BaseTransformer
{
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
     * @var array
     */
    protected array $defaultIncludes = [
        'data'
    ];

    /**
     * PaginatedActivityTransformer constructor
     *
     * @param EloquentCollection|null $activityImages
     * @param int $perPage
     * @param int $page
     */
    public function __construct(
        EloquentCollection $activityImages = null,
        int $perPage = 18,
        int $page = 1
    )
    {
        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;

        /** @var int perPage */
        $this->perPage = $perPage;

        /** @var int page */
        $this->page = $page;
    }

    /**
     * @param EloquentCollection $activities
     *
     * @return array
     */
    public function transform(EloquentCollection $activities) : array
    {
        return [
            'pagination' => [
                'page'  => $this->page,
                'by'    => $this->perPage,
                'total' => ceil($activities->count() / $this->perPage)
            ]
        ];
    }

    /**
     * @param EloquentCollection $activities
     *
     * @return Collection|null
     */
    public function includeData(EloquentCollection $activities) : ?Collection
    {
        return $this->collection(
            $activities->forPage($this->page, $this->perPage),
            new ActivityTransformer(
                $this->activityImages
            )
        );
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
