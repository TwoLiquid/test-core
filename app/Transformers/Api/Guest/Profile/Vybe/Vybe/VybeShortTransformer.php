<?php

namespace App\Transformers\Api\Guest\Profile\Vybe\Vybe;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class VybeShortTransformer
 *
 * @package App\Transformers\Api\Guest\Profile\Vybe\Vybe
 */
class VybeShortTransformer extends BaseTransformer
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
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * VybeShortTransformer constructor
     *
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     */
    public function __construct(
        EloquentCollection $vybeImages = null,
        EloquentCollection $vybeVideos = null
    )
    {
        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'images',
        'videos',
        'owner',
        'access',
        'age_limit'
    ];

    /**
     * @param Vybe $vybe
     *
     * @return array
     *
     * @throws DatabaseException
     */
    public function transform(Vybe $vybe) : array
    {
        return [
            'id'          => $vybe->id,
            'title'       => $vybe->title,
            'is_favorite' => AuthService::user() && $this->vybeRepository->isUserFavorite(
                    $vybe,
                    AuthService::user()
                ),
            'images_ids'  => $vybe->images_ids,
            'videos_ids'  => $vybe->videos_ids
        ];
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection|null
     */
    public function includeImages(Vybe $vybe) : ?Collection
    {
        $vybeImages = $this->vybeImages?->filter(function ($item) use ($vybe) {
            return !is_null($vybe->images_ids) && in_array($item->id, $vybe->images_ids);
        });

        return $vybeImages ? $this->collection($vybeImages, new VybeImageTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection|null
     */
    public function includeVideos(Vybe $vybe) : ?Collection
    {
        $vybeVideos = $this->vybeVideos?->filter(function ($item) use ($vybe) {
            return !is_null($vybe->videos_ids) && in_array($item->id, $vybe->videos_ids);
        });

        return $vybeVideos ? $this->collection($vybeVideos, new VybeVideoTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeOwner(Vybe $vybe) : ?Item
    {
        $owner = null;

        if ($vybe->relationLoaded('user')) {
            $owner = $vybe->user;
        }

        return $owner ? $this->item($owner, new UserTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeAccess(Vybe $vybe) : ?Item
    {
        $access = $vybe->getAccess();

        return $access ? $this->item($access, new VybeAccessTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeAgeLimit(Vybe $vybe) : ?Item
    {
        $ageLimit = $vybe->getAgeLimit();

        return $ageLimit ? $this->item($ageLimit, new VybeAgeLimitTransformer) : null;
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
