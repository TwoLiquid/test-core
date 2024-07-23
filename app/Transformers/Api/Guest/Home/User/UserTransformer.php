<?php

namespace App\Transformers\Api\Guest\Home\User;

use App\Models\MySql\User\User;
use App\Transformers\Api\Guest\Home\Vybe\VybeTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Api\Guest\Hpme\User
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userAvatars;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userBackgrounds;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userVoiceSamples;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userVideos;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeVideos;

    /**
     * UserTransformer constructor
     *
     * @param EloquentCollection|null $userAvatars
     * @param EloquentCollection|null $userBackgrounds
     * @param EloquentCollection|null $userVoiceSamples
     * @param EloquentCollection|null $userImages
     * @param EloquentCollection|null $userVideos
     * @param EloquentCollection|null $vybeImages
     * @param EloquentCollection|null $vybeVideos
     */
    public function __construct(
        EloquentCollection $userAvatars = null,
        EloquentCollection $userBackgrounds = null,
        EloquentCollection $userVoiceSamples = null,
        EloquentCollection $userImages = null,
        EloquentCollection $userVideos = null,
        EloquentCollection $vybeImages = null,
        EloquentCollection $vybeVideos = null
    )
    {
        /** @var EloquentCollection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var EloquentCollection userBackgrounds */
        $this->userBackgrounds = $userBackgrounds;

        /** @var EloquentCollection userVoiceSamples */
        $this->userVoiceSamples = $userVoiceSamples;

        /** @var EloquentCollection userImages */
        $this->userImages = $userImages;

        /** @var EloquentCollection userVideos */
        $this->userVideos = $userVideos;

        /** @var EloquentCollection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVideos = $vybeVideos;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'avatar',
        'background',
        'voice_sample',
        'images',
        'videos',
        'vybes',
        'verification_status'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'              => $user->id,
            'auth_id'         => $user->auth_id,
            'username'        => $user->username,
            'followers_count' => $user->subscribers_count ? $user->subscribers_count : null,
            'following_count' => $user->subscriptions_count ? $user->subscriptions_count : null
        ];
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeAvatar(User $user) : ?Item
    {
        $userAvatar = $this->userAvatars?->filter(function ($item) use ($user) {
            return $item->id == $user->avatar_id;
        })->first();

        return $userAvatar ? $this->item($userAvatar, new UserAvatarTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeBackground(User $user) : ?Item
    {
        $userBackground = $this->userBackgrounds?->filter(function ($item) use ($user) {
            return $item->id == $user->background_id;
        })->first();

        return $userBackground ? $this->item($userBackground, new UserBackgroundTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeVoiceSample(User $user) : ?Item
    {
        $userVoiceSample = $this->userVoiceSamples?->filter(function ($item) use ($user) {
            return $item->id == $user->voice_sample_id;
        })->first();

        return $userVoiceSample ? $this->item($userVoiceSample, new UserVoiceSampleTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeImages(User $user) : ?Collection
    {
        $userImages = $this->userImages?->filter(function ($item) use ($user) {
            return !is_null($user->images_ids) && in_array($item->id, $user->images_ids);
        });

        return $userImages ? $this->collection($userImages, new UserImageTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeVideos(User $user) : ?Collection
    {
        $userVideos = $this->userVideos?->filter(function ($item) use ($user) {
            return !is_null($user->videos_ids) && in_array($item->id, $user->videos_ids);
        });

        return $userVideos ? $this->collection($userVideos, new UserVideoTransformer) : null;
    }

    /**
     * @param User $user
     *
     * @return Collection|null
     */
    public function includeVybes(User $user): ?Collection
    {
        $vybes = null;

        if ($user->relationLoaded('vybes')) {
            $vybes = $user->vybes;
        }

        return $vybes ? $this->collection($vybes, new VybeTransformer(
            null,
            $this->vybeImages,
            $this->vybeVideos
        )) : null;
    }

    /**
     * @param User $user
     *
     * @return Item|null
     */
    public function includeVerificationStatus(User $user): ?Item
    {
        $userIdVerificationStatus = $user->getIdVerificationStatus();

        return $userIdVerificationStatus ? $this->item($userIdVerificationStatus, new UserIdVerificationStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'users';
    }
}
