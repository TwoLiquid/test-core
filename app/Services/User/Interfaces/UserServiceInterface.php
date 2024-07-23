<?php

namespace App\Services\User\Interfaces;

use App\Lists\User\Balance\Status\UserBalanceStatusListItem;
use App\Models\MySql\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface UserServiceInterface
 *
 * @package App\Services\User\Interfaces
 */
interface UserServiceInterface
{
    /**
     * This method provides attaching existing rows by related
     * entity repository
     *
     * @param User $user
     * @param array $personalityTraits
     */
    public function attachRegisterPersonalityTraitsToUser(
        User $user,
        array $personalityTraits
    ) : void;

    /**
     * This method provides updating existing rows
     * by related entity repository
     *
     * @param User $user
     * @param array $personalityTraitsItems
     */
    public function updateUserPersonalityTraits(
        User $user,
        array $personalityTraitsItems
    ) : void;

    /**
     * This method provides attaching existing row by related
     * entity repository
     *
     * @param User $user
     * @param array $languages
     */
    public function attachRegisterLanguagesToUser(
        User $user,
        array $languages
    ) : void;

    /**
     * This method provides updating existing rows by related
     * entity repository
     *
     * @param User $user
     * @param array $languagesItems
     */
    public function updateLanguagesToUser(
        User $user,
        array $languagesItems
    ) : void;

    /**
     * This method provides attaching existing row by related
     * entity repository with belonging to many relations
     *
     * @param User $user
     */
    public function attachNotificationSettingDefaultValuesToUser(
        User $user
    ) : void;

    /**
     * This method provides validating data
     *
     * @param array $files
     */
    public function validateUserAlbum(
        array $files
    ) : void;

    /**
     * This method provides uploading data
     * by related entity repository with a certain query
     *
     * @param User $user
     * @param array $files
     */
    public function uploadUserAlbum(
        User $user,
        array $files
    ) : void;

    /**
     * This method provides uploading data
     * by related entity repository with a certain query
     *
     * @param User $user
     * @param array $files
     */
    public function uploadUserImages(
        User $user,
        array $files
    ) : void;

    /**
     * This method provides uploading data
     * by related entity repository with a certain query
     *
     * @param User $user
     * @param array $files
     */
    public function uploadUserVideos(
        User $user,
        array $files
    ) : void;

    /**
     * This method provides creating data
     * by related entity repository
     *
     * @param User $user
     * @param UserBalanceStatusListItem|null $userBalanceStatusListItem
     */
    public function createUserBalances(
        User $user,
        ?UserBalanceStatusListItem $userBalanceStatusListItem
    ) : void;

    /**
     * This method provides checking data
     *
     * @param string|null $emailVerifiedAt
     *
     * @return bool
     */
    public function checkEmailIsNotVerified(
        ?string $emailVerifiedAt
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param User $user
     *
     * @return string
     */
    public function getFastOrderPageUrl(
        User $user
    ) : string;

    /**
     * This method provides checking data
     *
     * @param User $user
     *
     * @return bool
     */
    public function isLastProfileRequestAccepted(
        User $user
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param User $user
     *
     * @return bool
     */
    public function checkLoginAttempts(
        User $user
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param User $user
     *
     * @return User|null
     */
    public function decreaseLoginAttempts(
        User $user
    ) : ?User;

    /**
     * This method provides checking data
     *
     * @param User $user
     *
     * @return bool
     */
    public function checkEmailLastChangeValid(
        User $user
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param User $user
     *
     * @return bool
     */
    public function checkEmailAttempts(
        User $user
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param User $user
     *
     * @return User|null
     */
    public function decreaseEmailAttempts(
        User $user
    ) : ?User;

    /**
     * This method provides checking data
     *
     * @param User $user
     *
     * @return bool
     */
    public function checkPasswordAttempts(
        User $user
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param User $user
     *
     * @return User|null
     */
    public function decreasePasswordAttempts(
        User $user
    ) : ?User;

    /**
     * This method provides checking data
     *
     * @param string $login
     *
     * @return bool
     */
    public function checkLoginIsEmail(
        string $login
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param User $user
     * @param int $avatarId
     *
     * @return bool
     */
    public function deleteAvatarId(
        User $user,
        int $avatarId
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param User $user
     * @param int $backgroundId
     *
     * @return bool
     */
    public function deleteBackgroundId(
        User $user,
        int $backgroundId
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param User $user
     * @param int $voiceSampleId
     *
     * @return bool
     */
    public function deleteVoiceSampleId(
        User $user,
        int $voiceSampleId
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param User $user
     * @param array $imagesIds
     *
     * @return bool
     */
    public function deleteImagesIds(
        User $user,
        array $imagesIds
    ) : bool;

    /**
     * This method provides updating data
     *
     * @param User $user
     * @param array $videosIds
     *
     * @return bool
     */
    public function deleteVideosIds(
        User $user,
        array $videosIds
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param string $birthDate
     *
     * @return bool
     */
    public function isBirthDateAllowed(
        string $birthDate
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getByOrderItems(
        Collection $orderItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $cartItems
     *
     * @return Collection
     */
    public function getByCartItems(
        Collection $cartItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $users
     *
     * @return Collection
     */
    public function getByFullProfile(
        Collection $users
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $vybes
     *
     * @return Collection
     */
    public function getByVybes(
        Collection $vybes
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $timeslots
     *
     * @return Collection
     */
    public function getByTimeslots(
        Collection $timeslots
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param array $slots
     *
     * @return Collection
     */
    public function getUsersFromSlots(
        array $slots
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param Collection $usersAvatars
     * @param array $slots
     *
     * @return array
     */
    public function updateSlotsWithAvatars(
        Collection $usersAvatars,
        array $slots
    ) : array;

    /**
     * This method provides getting data
     *
     * @param User $user
     *
     * @return float
     */
    public function getTotalTaxPercent(
        User $user
    ) : float;

    /**
     * This method provides deleting data
     *
     * @param User $user
     */
    public function delete(
        User $user
    ) : void;

    /**
     * This method provides checking data
     *
     * @param User $user
     * @param User $subscription
     *
     * @return bool
     */
    public function isSubscription(
        User $user,
        User $subscription
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param User $user
     * @param User $subscriber
     *
     * @return bool
     */
    public function isSubscriber(
        User $user,
        User $subscriber
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param User $user
     * @param User $blockedUser
     *
     * @return bool
     */
    public function isBlocked(
        User $user,
        User $blockedUser
    ) : bool;
}
