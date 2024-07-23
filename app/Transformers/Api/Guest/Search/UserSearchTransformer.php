<?php

namespace App\Transformers\Api\Guest\Search;

use App\Transformers\Api\Guest\Search\User\UserTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class UserSearchTransformer
 *
 * @package App\Transformers\Api\Guest\Search
 */
class UserSearchTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $users;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userVoiceSamples;

    /**
     * UserSearchTransformer constructor
     *
     * @param EloquentCollection|null $users
     * @param EloquentCollection|null $userAvatars
     * @param EloquentCollection|null $userVoiceSamples
     */
    public function __construct(
        ?EloquentCollection $users = null,
        ?EloquentCollection $userAvatars = null,
        ?EloquentCollection $userVoiceSamples = null
    )
    {
        /** @var EloquentCollection users */
        $this->users = $users;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var EloquentCollection userVoiceSamples */
        $this->userVoiceSamples = $userVoiceSamples;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'users'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'users_count' => $this->users ? $this->users->count() : 0
        ];
    }

    /**
     * @return Collection|null
     */
    public function includeUsers() : ?Collection
    {
        return $this->users ?
            $this->collection(
                $this->users,
                new UserTransformer(
                    $this->userAvatars,
                    $this->userVoiceSamples
                )
            ) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_search';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_searches';
    }
}
