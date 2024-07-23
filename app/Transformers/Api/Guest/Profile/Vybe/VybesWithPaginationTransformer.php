<?php

namespace App\Transformers\Api\Guest\Profile\Vybe;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class VybesWithPaginationTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Vybe
 */
class VybesWithPaginationTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $vybes;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $favoriteVybes;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $activities;

    /**
     * @var int
     */
    protected int $perPage;

    /**
     * @var int
     */
    protected int $perFavoritePage;

    /**
     * @var int
     */
    protected int $page;

    /**
     * @var int
     */
    protected int $favoritePage;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybes',
        'favorite_vybes',
        'activities'
    ];

    /**
     * VybesWithPaginationTransformer constructor
     *
     * @param EloquentCollection $vybes
     * @param EloquentCollection $favoriteVybes
     * @param EloquentCollection $activities
     * @param int $perPage
     * @param int $perFavoritePage
     * @param int $page
     * @param int $favoritePage
     */
    public function __construct(
        EloquentCollection $vybes,
        EloquentCollection $favoriteVybes,
        EloquentCollection $activities,
        int $perPage,
        int $perFavoritePage,
        int $page = 1,
        int $favoritePage = 1
    )
    {
        $this->vybes = $vybes;
        $this->favoriteVybes = $favoriteVybes;
        $this->activities = $activities;
        $this->perPage = $perPage;
        $this->perFavoritePage = $perFavoritePage;
        $this->page = $page;
        $this->favoritePage = $favoritePage;
    }

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item|null
     */
    public function includeVybes() : ?Item
    {
        return $this->item(
            $this->vybes,
            new PaginatedVybeTransformer(
                $this->vybes,
                $this->perPage,
                $this->page
            )
        );
    }

    /**
     * @return Item|null
     */
    public function includeFavoriteVybes() : ?Item
    {
        return $this->item(
            $this->favoriteVybes,
            new PaginatedVybeTransformer(
                $this->favoriteVybes,
                $this->perFavoritePage,
                $this->favoritePage
            )
        );
    }

    /**
     * @return Collection|null
     */
    public function includeActivities() : ?Collection
    {
        return $this->collection(
            $this->activities,
            new FilterActivityTransformer(
                $this->vybes
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
