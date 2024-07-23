<?php

namespace App\Transformers\Api\Guest\Search;

use App\Models\MySql\User\User;
use App\Transformers\Api\Guest\Search\Vybe\VybeTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class VybeSearchTransformer
 *
 * @package App\Transformers\Api\Guest\Search
 */
class VybeSearchTransformer extends BaseTransformer
{
    /**
     * @var User|null
     */
    protected ?User $user;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybes;

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
     * VybeSearchTransformer constructor
     *
     * @param User|null $user
     * @param EloquentCollection|null $vybes
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     * @param EloquentCollection|null $activityImages
     */
    public function __construct(
        ?User $user = null,
        ?EloquentCollection $vybes = null,
        ?EloquentCollection $vybeImages = null,
        ?EloquentCollection $vybeVideos = null,
        ?EloquentCollection $activityImages = null
    )
    {
        /** @var User user */
        $this->user = $user;

        /** @var EloquentCollection vybes */
        $this->vybes = $vybes;

        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var EloquentCollection activityImages */
        $this->activityImages = $activityImages;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybes'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'vybes_count' => $this->vybes ? $this->vybes->count() : 0
        ];
    }

    /**
     * @return Collection|null
     */
    public function includeVybes() : ?Collection
    {
        return $this->vybes ?
            $this->collection(
                $this->vybes,
                new VybeTransformer(
                    $this->user,
                    $this->vybeImages,
                    $this->vybeVideos,
                    $this->activityImages
                )
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_search';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_searches';
    }
}
