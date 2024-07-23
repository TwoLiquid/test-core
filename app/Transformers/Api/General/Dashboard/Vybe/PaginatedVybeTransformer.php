<?php

namespace App\Transformers\Api\General\Dashboard\Vybe;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class PaginatedVybeTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Vybe
 */
class PaginatedVybeTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeVideos;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activityImages;

    /**
     * @var int|null
     */
    protected ?int $count;

    /**
     * @var int|null
     */
    protected ?int $perPage;

    /**
     * @var int|null
     */
    protected ?int $page;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'data'
    ];

    /**
     * PaginatedVybeTransformer constructor
     *
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     * @param EloquentCollection|null $activityImages
     * @param int|null $count
     * @param int|null $perPage
     * @param int|null $page
     */
    public function __construct(
        ?EloquentCollection $vybeImages = null,
        ?EloquentCollection $vybeVideos = null,
        ?EloquentCollection $activityImages = null,
        ?int $count = null,
        ?int $perPage = 18,
        ?int $page = 1
    )
    {
        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;

        /** @var int count */
        $this->count = $count;

        /** @var int perPage */
        $this->perPage = $perPage;

        /** @var int page */
        $this->page = $page;
    }

    /**
     * @param EloquentCollection $vybes
     *
     * @return array[]
     */
    public function transform(EloquentCollection $vybes) : array
    {
        return [
            'count'      => $this->count,
            'pagination' => [
                'page'  => $this->page,
                'by'    => $this->perPage,
                'total' => ceil($vybes->count() / $this->perPage)
            ]
        ];
    }

    /**
     * @param EloquentCollection $vybes
     *
     * @return Collection|null
     */
    public function includeData(EloquentCollection $vybes) : ?Collection
    {
        return $this->collection(
            $vybes->forPage($this->page, $this->perPage),
            new VybeTransformer(
                $this->vybeImages,
                $this->vybeVideos,
                $this->activityImages
            )
        );
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybes';
    }
}
