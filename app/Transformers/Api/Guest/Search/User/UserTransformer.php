<?php

namespace App\Transformers\Api\Guest\Search\User;

use App\Models\MySql\User\User;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserTransformer
 *
 * @package App\Transformers\Services\Search\User
 */
class UserTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $userAvatars;

    /**
     * @var Collection|null
     */
    protected ?Collection $userVoiceSamples;

    /**
     * UserTransformer constructor
     *
     * @param Collection|null $userAvatars
     * @param Collection|null $userVoiceSamples
     */
    public function __construct(
        Collection $userAvatars = null,
        Collection $userVoiceSamples = null
    )
    {
        /** @var Collection userAvatars */
        $this->userAvatars = $userAvatars;

        /** @var Collection userVoiceSamples */
        $this->userVoiceSamples = $userVoiceSamples;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'avatar',
        'voice_sample'
    ];

    /**
     * @param User $user
     *
     * @return array
     */
    public function transform(User $user) : array
    {
        return [
            'id'          => $user->id,
            'auth_id'     => $user->auth_id,
            'username'    => $user->username,
            'vybes_count' => $user->vybes_count ? $user->vybes_count : null
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
    public function includeVoiceSample(User $user) : ?Item
    {
        $voiceSamples = $this->userVoiceSamples?->filter(function ($item) use ($user) {
            return $item->id == $user->voice_sample_id;
        })->first();

        return $voiceSamples ? $this->item($voiceSamples, new UserVoiceSampleTransformer) : null;
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
