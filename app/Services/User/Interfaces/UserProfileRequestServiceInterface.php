<?php

namespace App\Services\User\Interfaces;

use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Microservices\Media\Responses\UserImageCollectionResponse;
use App\Microservices\Media\Responses\UserVideoCollectionResponse;
use App\Models\MongoDb\User\Request\Profile\UserProfileRequest;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserProfileRequestServiceInterface
 *
 * @package App\Services\User\Interfaces
 */
interface UserProfileRequestServiceInterface
{
    /**
     * This method provides checking data
     *
     * @param array|null $files
     * @return bool
     */
    public function albumHasImages(
        ?array $files
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param array|null $files
     *
     * @return bool
     */
    public function albumHasVideos(
        ?array $files
    ) : bool;

    /**
     * This method provides uploading data
     * by related entity repository with a certain query
     *
     * @param UserProfileRequest $userProfileRequest
     * @param array $files
     *
     * @return UserImageCollectionResponse|null
     */
    public function uploadImages(
        UserProfileRequest $userProfileRequest,
        array $files
    ) : ?UserImageCollectionResponse;

    /**
     * This method provides uploading data
     * by related entity repository with a certain query
     *
     * @param UserProfileRequest $userProfileRequest
     * @param array $files
     *
     * @return UserVideoCollectionResponse|null
     */
    public function uploadVideos(
        UserProfileRequest $userProfileRequest,
        array $files
    ) : ?UserVideoCollectionResponse;

    /**
     * This method provides checking data
     * by related entity repositories with a certain query
     *
     * @param UserProfileRequest $userProfileRequest
     *
     * @return bool
     */
    public function isAccepted(
        UserProfileRequest $userProfileRequest
    ) : bool;

    /**
     * This method provides checking data
     * by related entity repositories with a certain query
     *
     * @param UserProfileRequest $userProfileRequest
     *
     * @return bool
     */
    public function isDeclined(
        UserProfileRequest $userProfileRequest
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param RequestFieldStatusListItem|null $accountStatusStatus
     * @param RequestFieldStatusListItem|null $usernameStatus
     * @param RequestFieldStatusListItem|null $birthdateStatus
     * @param RequestFieldStatusListItem|null $descriptionStatus
     * @param RequestFieldStatusListItem|null $voiceSampleStatus
     * @param RequestFieldStatusListItem|null $avatarStatus
     * @param RequestFieldStatusListItem|null $backgroundStatus
     * @param RequestFieldStatusListItem|null $albumStatus
     *
     * @return bool
     */
    public function isProcessedForPendingUser(
        ?RequestFieldStatusListItem $accountStatusStatus,
        ?RequestFieldStatusListItem $usernameStatus,
        ?RequestFieldStatusListItem $birthdateStatus,
        ?RequestFieldStatusListItem $descriptionStatus,
        ?RequestFieldStatusListItem $voiceSampleStatus,
        ?RequestFieldStatusListItem $avatarStatus,
        ?RequestFieldStatusListItem $backgroundStatus,
        ?RequestFieldStatusListItem $albumStatus
    ) : bool;

    /**
     * This method provides checking data
     * by related entity repositories with a certain query
     *
     * @param UserProfileRequest $userProfileRequest
     * @param RequestFieldStatusListItem|null $accountStatusStatus
     * @param RequestFieldStatusListItem|null $usernameStatus
     * @param RequestFieldStatusListItem|null $birthdateStatus
     * @param RequestFieldStatusListItem|null $descriptionStatus
     * @param RequestFieldStatusListItem|null $voiceSampleStatus
     * @param RequestFieldStatusListItem|null $avatarStatus
     * @param RequestFieldStatusListItem|null $backgroundStatus
     * @param RequestFieldStatusListItem|null $albumStatus
     *
     * @return bool
     */
    public function isParametersProcessed(
        UserProfileRequest $userProfileRequest,
        ?RequestFieldStatusListItem $accountStatusStatus,
        ?RequestFieldStatusListItem $usernameStatus,
        ?RequestFieldStatusListItem $birthdateStatus,
        ?RequestFieldStatusListItem $descriptionStatus,
        ?RequestFieldStatusListItem $voiceSampleStatus,
        ?RequestFieldStatusListItem $avatarStatus,
        ?RequestFieldStatusListItem $backgroundStatus,
        ?RequestFieldStatusListItem $albumStatus
    ) : bool;

    /**
     * This method provides updating data
     * by related entity repositories with a certain query
     *
     * @param UserProfileRequest $userProfileRequest
     *
     * @return UserProfileRequest
     */
    public function acceptAll(
        UserProfileRequest $userProfileRequest
    ) : UserProfileRequest;

    /**
     * This method provides updating data
     * by related entity repositories with a certain query
     *
     * @param UserProfileRequest $userProfileRequest
     *
     * @return UserProfileRequest
     */
    public function declineAll(
        UserProfileRequest $userProfileRequest
    ) : UserProfileRequest;

    /**
     * This method provides getting data
     *
     * @param Collection|null $userProfileRequests
     *
     * @return Collection
     */
    public function getAllStatusesWithCounts(
        ?Collection $userProfileRequests
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param UserProfileRequest $userProfileRequest
     *
     * @return int|null
     */
    public function getDeletedVoiceSampleId(
        UserProfileRequest $userProfileRequest
    ) : ?int;

    /**
     * This method provides getting data
     *
     * @param UserProfileRequest $userProfileRequest
     *
     * @return int|null
     */
    public function getDeletedAvatarId(
        UserProfileRequest $userProfileRequest
    ) : ?int;

    /**
     * This method provides getting data
     *
     * @param UserProfileRequest $userProfileRequest
     *
     * @return int|null
     */
    public function getDeletedBackgroundId(
        UserProfileRequest $userProfileRequest
    ) : ?int;

    /**
     * This method provides getting data
     *
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array|null
     */
    public function getDeletedImagesIds(
        UserProfileRequest $userProfileRequest
    ) : ?array;

    /**
     * This method provides getting data
     *
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array|null
     */
    public function getDeletedVideosIds(
        UserProfileRequest $userProfileRequest
    ) : ?array;

    /**
     * This method provides updating data
     *
     * @param array|null $currentImagesIds
     * @param array|null $deletedImagesIds
     * @param array|null $uploadedImagesIds
     *
     * @return array|null
     */
    public function getUpdatedImagesIds(
        ?array $currentImagesIds,
        ?array $deletedImagesIds,
        ?array $uploadedImagesIds
    ) : ?array;

    /**
     * This method provides updating data
     *
     * @param array|null $currentVideosIds
     * @param array|null $deletedVideosIds
     * @param array|null $uploadedVideosIds
     *
     * @return array|null
     */
    public function getUpdatedVideosIds(
        ?array $currentVideosIds,
        ?array $deletedVideosIds,
        ?array $uploadedVideosIds
    ) : ?array;

    /**
     * This method provides creating data
     *
     * @param User $user
     * @param string $username
     * @param string $birthDate
     * @param string|null $description
     * @param bool|null $uploadedVoiceSample
     * @param bool|null $uploadedAvatar
     * @param bool|null $uploadedBackground
     * @param bool|null $uploadedImages
     * @param bool|null $uploadedVideos
     *
     * @return UserProfileRequest|null
     */
    public function createIfHasChanges(
        User $user,
        string $username,
        string $birthDate,
        ?string $description,
        ?bool $uploadedVoiceSample,
        ?bool $uploadedAvatar,
        ?bool $uploadedBackground,
        ?bool $uploadedImages,
        ?bool $uploadedVideos
    ) : ?UserProfileRequest;

    /**
     * This method provides updating data
     *
     * @param UserProfileRequest $userProfileRequest
     * @param RequestFieldStatusListItem|null $avatarStatus
     * @param RequestFieldStatusListItem|null $backgroundStatus
     * @param RequestFieldStatusListItem|null $voiceSampleStatus
     * @param RequestFieldStatusListItem|null $albumStatus
     */
    public function updateAcceptedMedia(
        UserProfileRequest $userProfileRequest,
        ?RequestFieldStatusListItem $avatarStatus,
        ?RequestFieldStatusListItem $backgroundStatus,
        ?RequestFieldStatusListItem $voiceSampleStatus,
        ?RequestFieldStatusListItem $albumStatus
    ) : void;
}
