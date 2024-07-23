<?php

namespace App\Transformers\Api\General\Dashboard\Vybe;

use App\Transformers\Api\General\Dashboard\Vybe\Form\VybeFormTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class VybePageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Vybe
 */
class VybePageTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $uncompletedVybes;

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
    protected ?EloquentCollection $activityImages;

    /**
     * @var int|null
     */
    protected ?int $uncompletedVybesCount;

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
     * VybePageTransformer constructor
     *
     * @param EloquentCollection|null $uncompletedVybes
     * @param EloquentCollection|null $soloVybes
     * @param EloquentCollection|null $groupVybes
     * @param EloquentCollection|null $eventVybes
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     * @param EloquentCollection|null $activityImages
     * @param int|null $uncompletedVybesCount
     * @param int|null $soloVybesCount
     * @param int|null $groupVybesCount
     * @param int|null $eventVybesCount
     * @param int|null $perPage
     * @param int|null $page
     */
    public function __construct(
        ?EloquentCollection $uncompletedVybes,
        ?EloquentCollection $soloVybes,
        ?EloquentCollection $groupVybes,
        ?EloquentCollection $eventVybes,
        ?EloquentCollection $vybeImages = null,
        ?EloquentCollection $vybeVideos = null,
        ?EloquentCollection $activityImages = null,
        ?int $uncompletedVybesCount = 0,
        ?int $soloVybesCount = 0,
        ?int $groupVybesCount = 0,
        ?int $eventVybesCount = 0,
        ?int $perPage = 18,
        ?int $page = 1
    )
    {
        /** @var EloquentCollection soloVybes */
        $this->uncompletedVybes = $uncompletedVybes;

        /** @var EloquentCollection soloVybes */
        $this->soloVybes = $soloVybes;

        /** @var EloquentCollection groupVybes */
        $this->groupVybes = $groupVybes;

        /** @var EloquentCollection $eventVybes */
        $this->eventVybes = $eventVybes;

        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;

        /** @var EloquentCollection uncompletedVybesCount */
        $this->uncompletedVybesCount = $uncompletedVybesCount;

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
        'uncompleted_vybes',
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
        return $this->item([], new VybeFormTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeUncompletedVybes() : ?Item
    {
        return $this->uncompletedVybes ?
            $this->item(
                $this->uncompletedVybes,
                new PaginatedVybeTransformer(
                    $this->vybeImages,
                    $this->vybeVideos,
                    $this->activityImages,
                    $this->uncompletedVybesCount,
                    $this->perPage,
                    $this->page
                )
            ) : null;
    }

    /**
     * @return Item|null
     */
    public function includeSoloVybes() : ?Item
    {
        return $this->soloVybes ?
            $this->item(
                $this->soloVybes,
                new PaginatedVybeTransformer(
                    $this->vybeImages,
                    $this->vybeVideos,
                    $this->activityImages,
                    $this->soloVybesCount,
                    $this->perPage,
                    $this->page
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
                new PaginatedVybeTransformer(
                    $this->vybeImages,
                    $this->vybeVideos,
                    $this->activityImages,
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
                new PaginatedVybeTransformer(
                    $this->vybeImages,
                    $this->vybeVideos,
                    $this->activityImages,
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
