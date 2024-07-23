<?php

namespace App\Transformers\Api\General\Dashboard\Profile\User\ProfileRequest;

use App\Models\MongoDb\User\Request\Profile\UserProfileRequest;
use App\Transformers\BaseTransformer;
use Carbon\Carbon;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class UserProfileRequestTransformer
 *
 * @package App\Transformers\Api\General\Dashboard\Profile\User\ProfileRequest
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

    public function __construct(
        EloquentCollection $userAvatars = null,
        EloquentCollection $userBackgrounds = null,
        EloquentCollection $userVoiceSamples = null,
        EloquentCollection $userImages = null,
        EloquentCollection $userVideos = null
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
        'account_status',
        'account_status_status',
        'username_status',
        'birth_date_status',
        'description_status',
        'voice_sample_status',
        'avatar_status',
        'background_status',
        'album_status',
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
            'id'                       => $userProfileRequest->_id,
            'username'                 => $userProfileRequest->username,
            'birth_date'               => $userProfileRequest->birth_date ?
                Carbon::parse($userProfileRequest->birth_date)->format('Y-m-d\TH:i:s.v\Z') :
                null,
            'description'              => $userProfileRequest->description,
            'previous_avatar_id'       => $userProfileRequest->previous_avatar_id,
            'previous_voice_sample_id' => $userProfileRequest->previous_voice_sample_id,
            'previous_background_id'   => $userProfileRequest->previous_background_id,
            'previous_images_ids'      => $userProfileRequest->previous_images_ids,
            'previous_videos_ids'      => $userProfileRequest->previous_videos_ids,
            'toast_message_text'       => $userProfileRequest->toast_message_text
        ];
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeAvatar(UserProfileRequest $userProfileRequest) : ?Item
    {
        $userAvatar = $this->userAvatars?->filter(function ($item) use ($userProfileRequest) {
            return $item->id == $userProfileRequest->avatar_id;
        })->first();

        return $userAvatar ? $this->item($userAvatar, new UserAvatarTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeBackground(UserProfileRequest $userProfileRequest) : ?Item
    {
        $userBackground = $this->userBackgrounds?->filter(function ($item) use ($userProfileRequest) {
            return $item->id == $userProfileRequest->background_id;
        })->first();

        return $userBackground ? $this->item($userBackground, new UserBackgroundTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeVoiceSample(UserProfileRequest $userProfileRequest) : ?Item
    {
        $userVoiceSample = $this->userVoiceSamples?->filter(function ($item) use ($userProfileRequest) {
            return $item->id == $userProfileRequest->voice_sample_id;
        })->first();

        return $userVoiceSample ? $this->item($userVoiceSample, new UserVoiceSampleTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Collection|null
     */
    public function includeImages(UserProfileRequest $userProfileRequest) : ?Collection
    {
        $userImages = $this->userImages?->filter(function ($item) use ($userProfileRequest) {
            return !is_null($userProfileRequest->images_ids) && in_array($item->id, $userProfileRequest->images_ids);
        });

        return $userImages ? $this->collection($userImages, new UserImageTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Collection|null
     */
    public function includeVideos(UserProfileRequest $userProfileRequest) : ?Collection
    {
        $userVideos = $this->userVideos?->filter(function ($item) use ($userProfileRequest) {
            return !is_null($userProfileRequest->videos_ids) && in_array($item->id, $userProfileRequest->videos_ids);
        });

        return $userVideos ? $this->collection($userVideos, new UserVideoTransformer) : null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return Item|null
     */
    public function includeAccountStatus(UserProfileRequest $userProfileRequest) : ?Item
    {
        $accountStatus = $userProfileRequest->getAccountStatus();

        return $accountStatus ? $this->item($accountStatus, new AccountStatusTransformer) : null;
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
