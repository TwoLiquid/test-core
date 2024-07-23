<?php

namespace App\Services\User;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Field\Status\RequestFieldStatusListItem;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Request\Status\RequestStatusListItem;
use App\Microservices\Media\MediaMicroservice;
use App\Microservices\Media\Responses\UserImageCollectionResponse;
use App\Microservices\Media\Responses\UserVideoCollectionResponse;
use App\Models\MongoDb\User\Request\Profile\UserProfileRequest;
use App\Models\MySql\User\User;
use App\Repositories\User\UserProfileRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\File\FileService;
use App\Services\User\Interfaces\UserProfileRequestServiceInterface;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserProfileRequestService
 *
 * @package App\Services\User
 */
class UserProfileRequestService implements UserProfileRequestServiceInterface
{
    /**
     * @var FileService
     */
    protected FileService $fileService;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * UserProfileRequestService constructor
     */
    public function __construct()
    {
        /** @var FileService fileService */
        $this->fileService = new FileService();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var UserProfileRequestRepository UserProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param array|null $files
     *
     * @return bool
     */
    public function albumHasImages(
        ?array $files
    ) : bool
    {
        if ($files) {
            $sortedFiles = $this->fileService->sortFiles($files);

            return isset($sortedFiles['images']);
        }

        return false;
    }

    /**
     * @param array|null $files
     *
     * @return bool
     */
    public function albumHasVideos(
        ?array $files
    ) : bool
    {
        if ($files) {
            $sortedFiles = $this->fileService->sortFiles($files);

            return isset($sortedFiles['videos']);
        }

        return false;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param array $files
     *
     * @return UserImageCollectionResponse|null
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function uploadImages(
        UserProfileRequest $userProfileRequest,
        array $files
    ) : ?UserImageCollectionResponse
    {
        $sortedFiles = $this->fileService->sortFiles($files);

        $images = [];

        if (isset($sortedFiles['images'])) {

            /** @var array $sortedFile */
            foreach ($sortedFiles['images'] as $sortedFile) {
                $images[] = [
                    'request_id' => $userProfileRequest->_id,
                    'content'    => $sortedFile['content'],
                    'mime'       => $sortedFile['mime'],
                    'extension'  => $sortedFile['extension']
                ];
            }

            return $this->mediaMicroservice->storeUserImages(
                $userProfileRequest->user,
                $images
            );
        }

        return null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param array $files
     *
     * @return UserVideoCollectionResponse|null
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function uploadVideos(
        UserProfileRequest $userProfileRequest,
        array $files
    ) : ?UserVideoCollectionResponse
    {
        $sortedFiles = $this->fileService->sortFiles($files);

        $videos = [];

        if (isset($sortedFiles['videos'])) {

            /** @var array $sortedFile */
            foreach ($sortedFiles['videos'] as $sortedFile) {
                $videos[] = [
                    'request_id' => $userProfileRequest->_id,
                    'content'    => $sortedFile['content'],
                    'mime'       => $sortedFile['mime'],
                    'extension'  => $sortedFile['extension']
                ];
            }

            return $this->mediaMicroservice->storeUserVideos(
                $userProfileRequest->user,
                $videos
            );
        }

        return null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return bool
     */
    public function isAccepted(
        UserProfileRequest $userProfileRequest
    ) : bool
    {
        $statuses = [
            $userProfileRequest->account_status_status_id,
            $userProfileRequest->username_status_id,
            $userProfileRequest->birth_date_status_id,
            $userProfileRequest->description_status_id,
            $userProfileRequest->voice_sample_status_id,
            $userProfileRequest->avatar_status_id,
            $userProfileRequest->background_status_id,
            $userProfileRequest->album_status_id
        ];

        /** @var int $status */
        foreach ($statuses as $status) {
            if (!is_null($status)) {
                if (!RequestFieldStatusList::isAcceptedItem($status)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return bool
     */
    public function isDeclined(
        UserProfileRequest $userProfileRequest
    ) : bool
    {
        $statuses = [
            $userProfileRequest->account_status_status_id,
            $userProfileRequest->username_status_id,
            $userProfileRequest->birth_date_status_id,
            $userProfileRequest->description_status_id,
            $userProfileRequest->voice_sample_status_id,
            $userProfileRequest->avatar_status_id,
            $userProfileRequest->background_status_id,
            $userProfileRequest->album_status_id
        ];

        /** @var int $status */
        foreach ($statuses as $status) {
            if (!is_null($status)) {
                if (RequestFieldStatusList::isDeclinedItem($status)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
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
    ) : bool
    {
        $statuses = [
            $accountStatusStatus?->id,
            $usernameStatus?->id,
            $birthdateStatus?->id,
            $descriptionStatus?->id,
            $voiceSampleStatus?->id,
            $avatarStatus?->id,
            $backgroundStatus?->id,
            $albumStatus?->id
        ];

        $match = null;

        /** @var int $status */
        foreach ($statuses as $status) {
            if (!is_null($status)) {
                if (!$match) {
                    $match = $status;
                } else if ($match != $status) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
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
    ) : bool
    {
        $statuses = [];

        if ($userProfileRequest->getAccountStatus()) {
            $statuses[] = $accountStatusStatus;
        }

        if ($userProfileRequest->getUsernameStatus()) {
            $statuses[] = $usernameStatus;
        }

        if ($userProfileRequest->getBirthdateStatus()) {
            $statuses[] = $birthdateStatus;
        }

        if ($userProfileRequest->getDescriptionStatus()) {
            $statuses[] = $descriptionStatus;
        }

        if ($userProfileRequest->getVoiceSampleStatus()) {
            $statuses[] = $voiceSampleStatus;
        }

        if ($userProfileRequest->getAvatarStatus()) {
            $statuses[] = $avatarStatus;
        }

        if ($userProfileRequest->getBackgroundStatus()) {
            $statuses[] = $backgroundStatus;
        }

        if ($userProfileRequest->getAlbumStatus()) {
            $statuses[] = $albumStatus;
        }

        /** @var RequestFieldStatusListItem $status */
        foreach ($statuses as $status) {
            if (!is_null($status)) {
                if (!$status->isAccepted() && !$status->isDeclined()) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return UserProfileRequest
     *
     * @throws DatabaseException
     */
    public function acceptAll(
        UserProfileRequest $userProfileRequest
    ) : UserProfileRequest
    {
        return $this->userProfileRequestRepository->update(
            $userProfileRequest,
            $userProfileRequest->account_status_status_id ? RequestFieldStatusList::getAcceptedItem() : null,
            $userProfileRequest->username_status_id ? RequestFieldStatusList::getAcceptedItem() : null,
            $userProfileRequest->birth_date_status_id ? RequestFieldStatusList::getAcceptedItem() : null,
            $userProfileRequest->description_status_id ? RequestFieldStatusList::getAcceptedItem() : null,
            $userProfileRequest->voice_sample_status_id ? RequestFieldStatusList::getAcceptedItem() : null,
            $userProfileRequest->avatar_status_id ? RequestFieldStatusList::getAcceptedItem() : null,
            $userProfileRequest->background_status_id ? RequestFieldStatusList::getAcceptedItem() : null,
            $userProfileRequest->album_status_id ? RequestFieldStatusList::getAcceptedItem() : null,
            null
        );
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return UserProfileRequest
     *
     * @throws DatabaseException
     */
    public function declineAll(
        UserProfileRequest $userProfileRequest
    ) : UserProfileRequest
    {
        return $this->userProfileRequestRepository->update(
            $userProfileRequest,
            $userProfileRequest->account_status_status_id ? RequestFieldStatusList::getDeclinedItem() : null,
            $userProfileRequest->username_status_id ? RequestFieldStatusList::getAcceptedItem() : null,
            $userProfileRequest->birth_date_status_id ? RequestFieldStatusList::getDeclinedItem() : null,
            $userProfileRequest->description_status_id ? RequestFieldStatusList::getDeclinedItem() : null,
            $userProfileRequest->voice_sample_status_id ? RequestFieldStatusList::getDeclinedItem() : null,
            $userProfileRequest->avatar_status_id ? RequestFieldStatusList::getDeclinedItem() : null,
            $userProfileRequest->background_status_id ? RequestFieldStatusList::getDeclinedItem() : null,
            $userProfileRequest->album_status_id ? RequestFieldStatusList::getDeclinedItem() : null,
            null
        );
    }

    /**
     * @param Collection|null $userProfileRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllStatusesWithCounts(
        ?Collection $userProfileRequests
    ) : Collection
    {
        $requestStatuses = new Collection();

        /** @var RequestStatusListItem $requestStatusListItem */
        foreach (RequestStatusList::getItems() as $requestStatusListItem) {

            /**
             * Checking profile requests existence
             */
            if ($userProfileRequests) {

                /**
                 * Getting request status count
                 */
                $count = $this->userProfileRequestRepository->getRequestStatusCountByIds(
                    $userProfileRequests->pluck('_id')
                        ->values()
                        ->toArray(),
                    $requestStatusListItem
                );
            } else {

                /**
                 * Getting request status count
                 */
                $count = $this->userProfileRequestRepository->getRequestStatusCount(
                    $requestStatusListItem
                );
            }

            /*
             * Setting count
             */
            $requestStatusListItem->setCount($count);

            /**
             * Adding request status to a response collection
             */
            $requestStatuses->add($requestStatusListItem);
        }

        return $requestStatuses;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return int|null
     */
    public function getDeletedVoiceSampleId(
        UserProfileRequest $userProfileRequest
    ) : ?int
    {
        if ($userProfileRequest->previous_voice_sample_id) {
            if ($userProfileRequest->previous_voice_sample_id != $userProfileRequest->voice_sample_id) {
                return $userProfileRequest->previous_voice_sample_id;
            }
        }

        return null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return int|null
     */
    public function getDeletedAvatarId(
        UserProfileRequest $userProfileRequest
    ) : ?int
    {
        if ($userProfileRequest->previous_avatar_id) {
            if ($userProfileRequest->previous_avatar_id != $userProfileRequest->avatar_id) {
                return $userProfileRequest->previous_avatar_id;
            }
        }

        return null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return int|null
     */
    public function getDeletedBackgroundId(
        UserProfileRequest $userProfileRequest
    ) : ?int
    {
        if ($userProfileRequest->previous_background_id) {
            if ($userProfileRequest->previous_background_id != $userProfileRequest->background_id) {
                return $userProfileRequest->previous_background_id;
            }
        }

        return null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array|null
     */
    public function getDeletedImagesIds(
        UserProfileRequest $userProfileRequest
    ) : ?array
    {
        if ($userProfileRequest->previous_images_ids) {

            /**
             * Getting deleted images ids
             */
            return array_diff(
                $userProfileRequest->previous_images_ids,
                $userProfileRequest->images_ids
            );
        }

        return null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array|null
     */
    public function getDeletedVideosIds(
        UserProfileRequest $userProfileRequest
    ) : ?array
    {
        if ($userProfileRequest->previous_videos_ids) {

            /**
             * Getting deleted videos ids
             */
            return array_diff(
                $userProfileRequest->previous_videos_ids,
                $userProfileRequest->videos_ids
            );
        }

        return null;
    }

    /**
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
    ) : ?array
    {
        $calculatedImagesIds = null;

        if ($uploadedImagesIds) {
            if ($currentImagesIds) {
                $calculatedImagesIds = array_merge(
                    $currentImagesIds,
                    $uploadedImagesIds
                );
            } else {
                $calculatedImagesIds = $uploadedImagesIds;
            }
        }

        if ($deletedImagesIds) {
            if ($calculatedImagesIds) {
                $calculatedImagesIds = array_diff(
                    $calculatedImagesIds,
                    $deletedImagesIds
                );
            } else {
                $calculatedImagesIds = array_diff(
                    $currentImagesIds,
                    $deletedImagesIds
                );
            }
        }

        return $calculatedImagesIds;
    }

    /**
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
    ) : ?array
    {
        $calculatedVideosIds = null;

        if ($uploadedVideosIds) {
            if ($currentVideosIds) {
                $calculatedVideosIds = array_merge(
                    $currentVideosIds,
                    $uploadedVideosIds
                );
            } else {
                $calculatedVideosIds = $uploadedVideosIds;
            }
        }

        if ($deletedVideosIds) {
            if ($calculatedVideosIds) {
                $calculatedVideosIds = array_diff(
                    $calculatedVideosIds,
                    $deletedVideosIds
                );
            } else {
                $calculatedVideosIds = array_diff(
                    $currentVideosIds,
                    $deletedVideosIds
                );
            }
        }

        return $calculatedVideosIds;
    }

    /**
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
     *
     * @throws DatabaseException
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
    ) : ?UserProfileRequest
    {
        $hasChanges = false;

        $userBirthDate = $user->birth_date->format('Y-m-d H:i:s');
        $newBirthDate = Carbon::parse($birthDate)->format('Y-m-d H:i:s');

        if ($user->username != $username ||
            $userBirthDate != $newBirthDate ||
            $user->description != $description ||
            $uploadedVoiceSample ||
            $uploadedAvatar ||
            $uploadedBackground ||
            $uploadedImages ||
            $uploadedVideos
        ) {
            $hasChanges = true;
        }

        if ($hasChanges) {
            return $this->userProfileRequestRepository->store(
                $user,
                null,
                null,
                ($user->username != $username) ? $username : null,
                ($user->username != $username) ? $user->username : null,
                ($userBirthDate != $newBirthDate) ? $birthDate : null,
                ($userBirthDate != $newBirthDate) ? $user->birth_date : null,
                ($user->description != $description) ? $description : null,
                ($user->description != $description) ? $user->description : null,
            );
        }

        return null;
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     * @param RequestFieldStatusListItem|null $avatarStatus
     * @param RequestFieldStatusListItem|null $backgroundStatus
     * @param RequestFieldStatusListItem|null $voiceSampleStatus
     * @param RequestFieldStatusListItem|null $albumStatus
     * @throws DatabaseException
     */
    public function updateAcceptedMedia(
        UserProfileRequest $userProfileRequest,
        ?RequestFieldStatusListItem $avatarStatus,
        ?RequestFieldStatusListItem $backgroundStatus,
        ?RequestFieldStatusListItem $voiceSampleStatus,
        ?RequestFieldStatusListItem $albumStatus
    ) : void
    {
        /**
         * Checking avatar status existence and acceptance
         */
        if ($avatarStatus && $avatarStatus->isAccepted()) {

            /**
             * Updating user
             */
            $this->userRepository->setAvatarId(
                $userProfileRequest->user,
                $userProfileRequest->avatar_id
            );
        }

        /**
         * Checking background status existence and acceptance
         */
        if ($backgroundStatus && $backgroundStatus->isAccepted()) {

            /**
             * Updating user
             */
            $this->userRepository->setBackgroundId(
                $userProfileRequest->user,
                $userProfileRequest->background_id
            );
        }

        /**
         * Checking voice sample status existence and acceptance
         */
        if ($voiceSampleStatus && $voiceSampleStatus->isAccepted()) {

            /**
             * Updating user
             */
            $this->userRepository->setVoiceSampleId(
                $userProfileRequest->user,
                $userProfileRequest->voice_sample_id
            );
        }

        /**
         * Checking avatar status existence and acceptance
         */
        if ($albumStatus && $albumStatus->isAccepted()) {

            /**
             * Checking images existence
             */
            if ($userProfileRequest->images_ids) {

                /**
                 * Updating user
                 */
                $this->userRepository->setImagesIds(
                    $userProfileRequest->user,
                    $userProfileRequest->images_ids
                );
            }

            /**
             * Checking videos existence
             */
            if ($userProfileRequest->videos_ids) {

                /**
                 * Updating user
                 */
                $this->userRepository->setVideosIds(
                    $userProfileRequest->user,
                    $userProfileRequest->videos_ids
                );
            }
        }
    }
}
