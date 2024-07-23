<?php

namespace App\Transformers\Api\General\Dashboard\Vybe;

use App\Transformers\Api\General\Dashboard\Vybe\Form\FavoriteVybeFormTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class FavoriteVybePageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Vybe
 */
class FavoriteVybePageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $soloVybes;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $groupVybes;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $eventVybes;

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
    protected ?EloquentCollection $userAvatars;

    /**
     * @var int|null
     */
    protected ?int $soloVybesCount;

    /**
     * @var int|null
     */
    protected ?int $groupVybesCount;

    /**
     * @var int|null
     */
    protected ?int $eventVybesCount;

    /**
     * @var int|null
     */
    protected ?int $perPage;

    /**
     * @var int|null
     */
    protected ?int $page;

    /**
     * FavoriteVybePageTransformer constructor
     *
     * @param EloquentCollection|null $soloVybes
     * @param EloquentCollection|null $groupVybes
     * @param EloquentCollection|null $eventVybes
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     * @param EloquentCollection|null $userAvatars
     * @param int|null $soloVybesCount
     * @param int|null $groupVybesCount
     * @param int|null $eventVybesCount
     * @param int|null $perPage
     * @param int|null $page
     */
    public function __construct(
        ?EloquentCollection $soloVybes,
        ?EloquentCollection $groupVybes,
        ?EloquentCollection $eventVybes,
        ?EloquentCollection $vybeImages = null,
        ?EloquentCollection $vybeVideos = null,
        ?EloquentCollection $userAvatars = null,
        ?int $soloVybesCount = 0,
        ?int $groupVybesCount = 0,
        ?int $eventVybesCount = 0,
        ?int $perPage = 18,
        ?int $page = 1
    )
    {
        /** @var EloquentCollection soloVybes */
        $this->soloVybes = $soloVybes;

        /** @var EloquentCollection $groupVybes */
        $this->groupVybes = $groupVybes;

        /** @var EloquentCollection $eventVybes */
        $this->eventVybes = $eventVybes;

        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var int soloVybesCount */
        $this->soloVybesCount = $soloVybesCount;

        /** @var int $groupVybesCount */
        $this->groupVybesCount = $groupVybesCount;

        /** @var int $eventVybesCount */
        $this->eventVybesCount = $eventVybesCount;

        /** @var int $perPage */
        $this->perPage = $perPage;

        /** @var int $page */
        $this->page = $page;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'solo_vybes',
        'group_vybes',
        'event_vybes'
    ];

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
    public function includeForm() : ?Item
    {
        return $this->item([], new FavoriteVybeFormTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeSoloVybes() : ?Item
    {
        return $this->soloVybes ?
            $this->item(
                $this->soloVybes,
                new PaginatedFavoriteVybeTransformer(
                    $this->vybeImages,
                    $this->vybeVideos,
                    $this->userAvatars,
                    $this->soloVybesCount,
                    $this->perPage, $this->page
                )
            ) : null;
    }

    /**
     * @return Item|null
     */
    public function includeGroupVybes() : ?Item
    {
        return $this->groupVybes ?
            $this->item(
                $this->groupVybes,
                new PaginatedFavoriteVybeTransformer(
                    $this->vybeImages,
                    $this->vybeVideos,
                    $this->userAvatars,
                    $this->groupVybesCount,
                    $this->perPage,
                    $this->page
                )
            ) : null;
    }

    /**
     * @return Item|null
     */
    public function includeEventVybes() : ?Item
    {
        return $this->eventVybes ?
            $this->item(
                $this->eventVybes,
                new PaginatedFavoriteVybeTransformer(
                    $this->vybeImages,
                    $this->vybeVideos,
                    $this->userAvatars,
                    $this->eventVybesCount,
                    $this->perPage,
                    $this->page
                )
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_pages';
    }
}
