<?php

namespace App\Transformers\Api\Admin\User\Request\Profile;

use App\Models\MongoDb\User\Request\Profile\UserProfileRequest;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

/**
 * Class UserProfileRequestTransformer
 *
 * @package App\Transformers\Api\Admin\User\Request\Profile
 */
class UserProfileRequestTransformer extends BaseTransformer
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
     * UserProfileRequestTransformer constructor
     *
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
        'avatar',
        'background',
        'voice_sample',
        'images',
        'videos',
        'user',
        'account_status_status',
        'username_status',
        'birth_date_status',
        'description_status',
        'voice_sample_status',
        'avatar_status',
        'background_status',
        'album_status',
        'toast_message_type',
        'admin',
        'request_status'
    ];

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     */
    public function transform(UserProfileRequest $userProfileRequest) : array
    {
        return [
            'id'                 => $userProfileRequest->_id,
            'username'           => $userProfileRequest->username,
            'birth_date'         => $userProfileRequest->birth_date,
            'description'        => $userProfileRequest->description,
            'toast_message_text' => $userProfileRequest->toast_message_text
        ];
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeAvatar(UserProfileRequest $userProfileRequest) : ?Item
    {
        $userProfileRequestAvatar = $this->userProfileRequestAvatars?->filter(function ($item) use ($userProfileRequest) {
            return $item->id == $userProfileRequest->avatar_id;
        })->first();

        return $userProfileRequestAvatar ? $this->item($userProfileRequestAvatar, new UserAvatarTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeBackground(UserProfileRequest $userProfileRequest) : ?Item
    {
        $userProfileRequestBackground = $this->userProfileRequestBackgrounds?->filter(function ($item) use ($userProfileRequest) {
            return $item->id == $userProfileRequest->background_id;
        })->first();

        return $userProfileRequestBackground ? $this->item($userProfileRequestBackground, new UserBackgroundTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeVoiceSample(UserProfileRequest $userProfileRequest) : ?Item
    {
        $userProfileRequestVoiceSample = $this->userProfileRequestVoiceSamples?->filter(function ($item) use ($userProfileRequest) {
            return $item->id == $userProfileRequest->voice_sample_id;
        })->first();

        return $userProfileRequestVoiceSample ? $this->item($userProfileRequestVoiceSample, new UserVoiceSampleTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Collection|null
     */
    public function includeImages(UserProfileRequest $userProfileRequest) : ?Collection
    {
        $userProfileRequestImages = null;

        if ($this->userProfileRequestImages) {
            $userProfileRequestImages = $this->userProfileRequestImages->filter(function ($item) use ($userProfileRequest) {
                return !is_null($userProfileRequest->images_ids) && in_array($item->id, $userProfileRequest->images_ids);
            });

            if ($userProfileRequestImages && $this->userImages) {
                $userProfileRequestImages = $userProfileRequestImages->diff($this->userImages);
            }
        }

        return $userProfileRequestImages ? $this->collection($userProfileRequestImages, new UserImageTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Collection|null
     */
    public function includeVideos(UserProfileRequest $userProfileRequest) : ?Collection
    {
        $userProfileRequestVideos = $this->userProfileRequestVideos?->filter(function ($item) use ($userProfileRequest) {
            return !is_null($userProfileRequest->videos_ids) && in_array($item->id, $userProfileRequest->videos_ids);
        });

        if ($userProfileRequestVideos && $this->userVideos) {
            $userProfileRequestVideos = $userProfileRequestVideos->diff($this->userVideos);
        }

        return $userProfileRequestVideos ? $this->collection($userProfileRequestVideos, new UserVideoTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeUser(UserProfileRequest $userProfileRequest) : ?Item
    {
        $user = $userProfileRequest->user;

        return $user ? $this->item($user, new UserTransformer(
            $this->userAvatars,
            $this->userBackgrounds,
            $this->userVoiceSamples,
            $this->userImages,
            $this->userVideos
        )) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeAccountStatusStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $accountStatusStatus = $userProfileRequest->getAccountStatusStatus();

        return $accountStatusStatus ? $this->item($accountStatusStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeUsernameStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $usernameStatus = $userProfileRequest->getUsernameStatus();

        return $usernameStatus ? $this->item($usernameStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeBirthdateStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $birthDateStatus = $userProfileRequest->getBirthdateStatus();

        return $birthDateStatus ? $this->item($birthDateStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeDescriptionStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $descriptionStatus = $userProfileRequest->getDescriptionStatus();

        return $descriptionStatus ? $this->item($descriptionStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeVoiceSampleStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $voiceSampleStatus = $userProfileRequest->getVoiceSampleStatus();

        return $voiceSampleStatus ? $this->item($voiceSampleStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeAvatarStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $avatarStatus = $userProfileRequest->getAvatarStatus();

        return $avatarStatus ? $this->item($avatarStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeBackgroundStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $backgroundStatus = $userProfileRequest->getBackgroundStatus();

        return $backgroundStatus ? $this->item($backgroundStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeAlbumStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $albumStatus = $userProfileRequest->getAlbumStatus();

        return $albumStatus ? $this->item($albumStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeToastMessageType(UserProfileRequest $userProfileRequest) : ?Item
    {
        $toastMessageType = $userProfileRequest->getToastMessageType();

        return $toastMessageType ? $this->item($toastMessageType, new ToastMessageTypeTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeAdmin(UserProfileRequest $userProfileRequest) : ?Item
    {
        $admin = $userProfileRequest->admin;

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeRequestStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $requestStatus = $userProfileRequest->getRequestStatus();

        return $requestStatus ? $this->item($requestStatus, new RequestStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_profile_request';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_profile_requests';
    }
}
