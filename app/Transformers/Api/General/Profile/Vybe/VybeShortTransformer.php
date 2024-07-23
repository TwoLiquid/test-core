<?php

namespace App\Transformers\Api\General\Profile\Vybe;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeShortTransformer
 *
 * @package App\Transformers\Api\General\Profile\Vybe
 */
class VybeShortTransformer extends BaseTransformer
{
    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * VybeShortTransformer constructor
     */
    public function __construct()
    {
        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'owner',
        'access'
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
