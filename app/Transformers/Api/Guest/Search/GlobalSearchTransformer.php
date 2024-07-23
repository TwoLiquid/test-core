<?php

namespace App\Transformers\Api\Guest\Search;

use App\Models\MySql\User\User;
use App\Transformers\Api\Guest\Search\Activity\ActivityTransformer;
use App\Transformers\Api\Guest\Search\User\UserTransformer;
use App\Transformers\Api\Guest\Search\Vybe\VybeTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class GlobalSearchTransformer
 *
 * @package App\Transformers\Api\Guest\Search
 */
class GlobalSearchTransformer extends BaseTransformer
{
    /**
     * @var User|null
     */
    protected ?User $user;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $activities;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $users;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybes;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userVoiceSamples;

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
     * GlobalSearchTransformer constructor
     *
     * @param User|null $user
     * @param EloquentCollection|null $activities
     * @param EloquentCollection|null $users
     * @param EloquentCollection|null $vybes
     * @param EloquentCollection|null $userAvatars
     * @param EloquentCollection|null $userVoiceSamples
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     * @param EloquentCollection|null $activityImages
     */
    public function __construct(
        ?User $user,
        ?EloquentCollection $activities = null,
        ?EloquentCollection $users = null,
        ?EloquentCollection $vybes = null,
        ?EloquentCollection $userAvatars = null,
        ?EloquentCollection $userVoiceSamples = null,
        ?EloquentCollection $vybeImages = null,
        ?EloquentCollection $vybeVideos = null,
        ?EloquentCollection $activityImages = null
    )
    {
        /** @var User user */
        $this->user = $user;

        /** @var EloquentCollection activities */
        $this->activities = $activities;

        /** @var EloquentCollection users */
        $this->users = $users;

        /** @var EloquentCollection vybes */
        $this->vybes = $vybes;

        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var EloquentCollection userVoiceSamples */
        $this->userVoiceSamples = $userVoiceSamples;

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
        'activities',
        'users',
        'vybes'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'activities_count' => $this->activities ? $this->activities->count() : 0,
            'users_count'      => $this->users ? $this->users->count() : 0,
            'vybes_count'      => $this->vybes ? $this->vybes->count() : 0
        ];
    }

    /**
     * @return Collection|null
     */
    public function includeActivities() : ?Collection
    {
        return $this->activities ?
            $this->collection(
                $this->activities,
                new ActivityTransformer(
                    $this->activityImages
                )
            ) : null;
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
        return 'global_search';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'global_searches';
    }
}
