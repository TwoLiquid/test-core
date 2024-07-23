<?php

namespace App\Transformers\Api\General\Dashboard\Profile;

use App\Models\MySql\User\User;
use App\Transformers\Api\General\Dashboard\Profile\Form\UserDashboardFormTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class ProfilePageTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Profile
 */
class ProfilePageTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $user;

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
    protected ?EloquentCollection $userProfileRequestAvatars;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userProfileRequestBackgrounds;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userProfileRequestVoiceSamples;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userProfileRequestImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $userProfileRequestVideos;

    /**
     * ProfilePageTransformer constructor
     *
     * @param User $user
     * @param EloquentCollection|null $userAvatars
     * @param EloquentCollection|null $userBackgrounds
     * @param EloquentCollection|null $userVoiceSamples
     * @param EloquentCollection|null $userImages
     * @param EloquentCollection|null $userVideos
     * @param EloquentCollection|null $userProfileRequestAvatars
     * @param EloquentCollection|null $userProfileRequestBackgrounds
     * @param EloquentCollection|null $userProfileRequestVoiceSamples
     * @param EloquentCollection|null $userProfileRequestImages
     * @param EloquentCollection|null $userProfileRequestVideos
     */
    public function __construct(
        User $user,
        EloquentCollection $userAvatars = null,
        EloquentCollection $userBackgrounds = null,
        EloquentCollection $userVoiceSamples = null,
        EloquentCollection $userImages = null,
        EloquentCollection $userVideos = null,
        EloquentCollection $userProfileRequestAvatars = null,
        EloquentCollection $userProfileRequestBackgrounds = null,
        EloquentCollection $userProfileRequestVoiceSamples = null,
        EloquentCollection $userProfileRequestImages = null,
        EloquentCollection $userProfileRequestVideos = null
    )
    {
        /** @var User user */
        $this->user = $user;

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

        /** @var EloquentCollection userProfileRequestAvatars */
        $this->userProfileRequestAvatars = $userProfileRequestAvatars;

        /** @var EloquentCollection userProfileRequestBackgrounds */
        $this->userProfileRequestBackgrounds = $userProfileRequestBackgrounds;

        /** @var EloquentCollection userProfileRequestVoiceSamples */
        $this->userProfileRequestVoiceSamples = $userProfileRequestVoiceSamples;

        /** @var EloquentCollection userProfileRequestImages */
        $this->userProfileRequestImages = $userProfileRequestImages;

        /** @var EloquentCollection userProfileRequestVideos */
        $this->userProfileRequestVideos = $userProfileRequestVideos;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'user'
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
        return $this->item([], new UserDashboardFormTransformer($this->user));
    }

    /**
     * @return Item|null
     */
    public function includeUser() : ?Item
    {
        return $this->item(
            $this->user,
            new UserTransformer(
                $this->userAvatars,
                $this->userBackgrounds,
                $this->userVoiceSamples,
                $this->userImages,
                $this->userVideos,
                $this->userProfileRequestAvatars,
                $this->userProfileRequestBackgrounds,
                $this->userProfileRequestVoiceSamples,
                $this->userProfileRequestImages,
                $this->userProfileRequestVideos
            )
        );
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'profile_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'profile_pages';
    }
}
