<?php

namespace App\Services\User;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Lists\Language\LanguageList;
use App\Lists\Language\Level\LanguageLevelList;
use App\Lists\Notification\Setting\NotificationSettingList;
use App\Lists\PersonalityTrait\PersonalityTraitList;
use App\Lists\User\Balance\Status\UserBalanceStatusList;
use App\Lists\User\Balance\Status\UserBalanceStatusListItem;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use App\Microservices\Media\MediaMicroservice;
use App\Models\MySql\CartItem;
use App\Models\MySql\Language;
use App\Models\MySql\Media\UserAvatar;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\PersonalityTrait\PersonalityTrait;
use App\Models\MySql\Timeslot;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Billing\BillingChangeRequestRepository;
use App\Repositories\Billing\BillingRepository;
use App\Repositories\Language\LanguageRepository;
use App\Repositories\Notification\NotificationSettingRepository;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use App\Repositories\PersonalityTrait\PersonalityTraitRepository;
use App\Repositories\Receipt\WithdrawalRequestRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Repositories\User\UserIdVerificationRequestRepository;
use App\Repositories\User\UserProfileRequestRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\File\FileService;
use App\Services\File\MediaService;
use App\Services\User\Interfaces\UserServiceInterface;
use App\Services\Vybe\VybeService;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class UserService
 *
 * @package App\Services\User
 */
class UserService implements UserServiceInterface
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
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * @var BillingRepository
     */
    protected BillingRepository $billingRepository;

    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

    /**
     * @var NotificationSettingRepository
     */
    protected NotificationSettingRepository $notificationSettingRepository;

    /**
     * @var PayoutMethodRequestRepository
     */
    protected PayoutMethodRequestRepository $payoutMethodRequestRepository;

    /**
     * @var PersonalityTraitRepository
     */
    protected PersonalityTraitRepository $personalityTraitRepository;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * @var LanguageRepository
     */
    protected LanguageRepository $languageRepository;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

    /**
     * @var UserIdVerificationRequestRepository
     */
    protected UserIdVerificationRequestRepository $userIdVerificationRequestRepository;

    /**
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * @var WithdrawalRequestRepository
     */
    protected WithdrawalRequestRepository $withdrawalRequestRepository;

    /**
     * UserService constructor
     */
    public function __construct()
    {
        /** @var FileService fileService */
        $this->fileService = new FileService();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var MediaMicroservice mediaService */
        $this->mediaService = new MediaService();

        /** @var BillingRepository billingRepository */
        $this->billingRepository = new BillingRepository();

        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();

        /** @var NotificationSettingRepository notificationSettingRepository */
        $this->notificationSettingRepository = new NotificationSettingRepository();

        /** @var PayoutMethodRequestRepository payoutMethodRequestRepository */
        $this->payoutMethodRequestRepository = new PayoutMethodRequestRepository();

        /** @var PersonalityTraitRepository personalityTraitRepository */
        $this->personalityTraitRepository = new PersonalityTraitRepository();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

        /** @var LanguageRepository languageRepository */
        $this->languageRepository = new LanguageRepository();

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();

        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();

        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();

        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();

        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();

        /** @var WithdrawalRequestRepository withdrawalRequestRepository */
        $this->withdrawalRequestRepository = new WithdrawalRequestRepository();
    }

    /**
     * @param User $user
     * @param array $personalityTraits
     *
     * @throws DatabaseException
     */
    public function attachRegisterPersonalityTraitsToUser(
        User $user,
        array $personalityTraits
    ) : void
    {
        /** @var int $personalityTrait */
        foreach ($personalityTraits as $personalityTrait) {
            $personalityTraitListItem = PersonalityTraitList::getItem(
                $personalityTrait
            );

            $this->personalityTraitRepository->store(
                $user,
                $personalityTraitListItem
            );
        }
    }

    /**
     * @param User $user
     * @param array $personalityTraitsItems
     *
     * @throws DatabaseException
     */
    public function updateUserPersonalityTraits(
        User $user,
        array $personalityTraitsItems
    ) : void
    {
        /** @var int $personalityTraitsItem */
        foreach ($personalityTraitsItems as $personalityTraitsItem) {
            $personalityTraitListItem = PersonalityTraitList::getItem(
                $personalityTraitsItem
            );

            if (!$this->personalityTraitRepository->existsForUser(
                $user,
                $personalityTraitListItem
            )) {
                $this->personalityTraitRepository->store(
                    $user,
                    $personalityTraitListItem
                );
            }
        }

        /** @var PersonalityTrait $personalityTrait */
        foreach ($user->personalityTraits as $personalityTrait) {
            if (!in_array($personalityTrait->trait_id, $personalityTraitsItems)) {
                $this->personalityTraitRepository->delete(
                    $personalityTrait
                );
            }
        }
    }

    /**
     * @param User $user
     * @param array $languages
     *
     * @throws DatabaseException
     */
    public function attachRegisterLanguagesToUser(
        User $user,
        array $languages
    ) : void
    {
        /** @var array $language */
        foreach ($languages as $language) {
            $languageListItem = LanguageList::getItem(
                $language['language_id']
            );

            $languageLevelListItem = LanguageLevelList::getItem(
                $language['language_level_id']
            );

            $this->languageRepository->store(
                $user,
                $languageListItem,
                $languageLevelListItem
            );
        }
    }

    /**
     * @param User $user
     * @param array $languagesItems
     *
     * @throws DatabaseException
     */
    public function updateLanguagesToUser(
        User $user,
        array $languagesItems
    ) : void
    {
        /** @var array $languagesItem */
        foreach ($languagesItems as $languagesItem) {
            $languageListItem = LanguageList::getItem(
                $languagesItem['language_id']
            );

            $languageLevelListItem = LanguageLevelList::getItem(
                $languagesItem['language_level_id']
            );

            $language = $this->languageRepository->findByIdUserAndLanguage(
                $user,
                $languageListItem
            );

            if (!$language) {
                $this->languageRepository->store(
                    $user,
                    $languageListItem,
                    $languageLevelListItem
                );
            } else {
                $this->languageRepository->update(
                    $language,
                    $user,
                    $languageListItem,
                    $languageLevelListItem
                );
            }
        }

        /** @var Language $language */
        foreach ($user->languages as $language) {
            $overlaps = false;

            /** @var array $languagesItem */
            foreach ($languagesItems as $languagesItem) {
                if ($languagesItem['language_id'] == $language->language_id) {
                    $overlaps = true;
                }
            }

            if ($overlaps === false) {
                $this->languageRepository->delete(
                    $language
                );
            }
        }
    }

    /**
     * @param User $user
     *
     * @throws DatabaseException
     */
    public function attachNotificationSettingDefaultValuesToUser(
        User $user
    ) : void
    {
        $notificationSettingListItems = NotificationSettingList::getItemsValuesAsArray();

        $this->notificationSettingRepository->store(
            $user,
            $notificationSettingListItems['notification_enable'],
            $notificationSettingListItems['email_followers_follows_you'],
            $notificationSettingListItems['email_followers_new_vybe'],
            $notificationSettingListItems['email_followers_new_event'],
            $notificationSettingListItems['messages_unread'],
            $notificationSettingListItems['email_orders_new'],
            $notificationSettingListItems['email_order_starts'],
            $notificationSettingListItems['email_order_pending'],
            $notificationSettingListItems['reschedule_info'],
            $notificationSettingListItems['review_new'],
            $notificationSettingListItems['review_waiting'],
            $notificationSettingListItems['withdrawals_info'],
            $notificationSettingListItems['email_invitation_info'],
            $notificationSettingListItems['tickets_new_order'],
            $notificationSettingListItems['miscellaneous_regarding'],
            $notificationSettingListItems['platform_followers_follows'],
            $notificationSettingListItems['platform_followers_new_vybe'],
            $notificationSettingListItems['platform_followers_new_event'],
            $notificationSettingListItems['platform_order_starts'],
            $notificationSettingListItems['platform_invitation_info'],
            $notificationSettingListItems['news_receive']
        );
    }

    /**
     * @param array $files
     *
     * @throws BaseException
     */
    public function validateUserAlbum(
        array $files
    ) : void
    {
        $sortedFiles = $this->fileService->sortFiles($files);

        if (isset($sortedFiles['images'])) {

            /** @var array $image */
            foreach ($sortedFiles['images'] as $image) {
                $this->mediaService->validateUserImage(
                    $image['content'],
                    $image['mime']
                );
            }
        }

        if (isset($sortedFiles['videos'])) {

            /** @var array $video */
            foreach ($sortedFiles['videos'] as $video) {
                $this->mediaService->validateUserVideo(
                    $video['content'],
                    $video['mime']
                );
            }
        }
    }

    /**
     * @param User $user
     * @param array $files
     *
     * @return void
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function uploadUserAlbum(
        User $user,
        array $files
    ) : void
    {
        $sortedFiles = $this->fileService->sortFiles($files);

        if (isset($sortedFiles['images'])) {
            $this->mediaMicroservice->storeUserImages(
                $user,
                $sortedFiles['images']
            );
        }

        if (isset($sortedFiles['videos'])) {
            $this->mediaMicroservice->storeUserVideos(
                $user,
                $sortedFiles['videos']
            );
        }
    }

    /**
     * @param User $user
     * @param array $files
     *
     * @return void
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function uploadUserImages(
        User $user,
        array $files
    ) : void
    {
        $sortedFiles = $this->fileService->sortFiles($files);

        if (isset($sortedFiles['images'])) {
            $this->mediaMicroservice->storeUserImages(
                $user,
                $sortedFiles['images']
            );
        }
    }

    /**
     * @param User $user
     * @param array $files
     *
     * @return void
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function uploadUserVideos(
        User $user,
        array $files
    ) : void
    {
        $sortedFiles = $this->fileService->sortFiles($files);

        if (isset($sortedFiles['videos'])) {
            $this->mediaMicroservice->storeUserVideos(
                $user,
                $sortedFiles['videos']
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceStatusListItem|null $userBalanceStatusListItem
     *
     * @throws DatabaseException
     */
    public function createUserBalances(
        User $user,
        ?UserBalanceStatusListItem $userBalanceStatusListItem = null
    ) : void
    {
        $userBalanceTypeListItems = UserBalanceTypeList::getItems();

        /** @var UserBalanceTypeListItem $userBalanceTypeListItem */
        foreach ($userBalanceTypeListItems as $userBalanceTypeListItem) {
            $this->userBalanceRepository->store(
                $user,
                $userBalanceTypeListItem,
                $userBalanceStatusListItem ?:
                    UserBalanceStatusList::getInactive()
            );
        }
    }

    /**
     * @param string|null $emailVerifiedAt
     *
     * @return bool
     */
    public function checkEmailIsNotVerified(
        ?string $emailVerifiedAt
    ) : bool
    {
        if (!$emailVerifiedAt) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function getFastOrderPageUrl(
        User $user
    ) : string
    {
        return config('app.url') . 'fast-order/' . strtolower($user->username);
    }

    /**
     * @param User $user
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function checkPendingAccountRequests(
        User $user
    ) : void
    {
        if ($this->userDeactivationRequestRepository->findPendingForUser(
            $user
        )) {
            throw new BaseException(
                 trans('exceptions/service/user/user.' . __FUNCTION__ . '.deactivationRequest'),
                null,
                422
            );
        }

        if ($this->userDeletionRequestRepository->findPendingForUser(
            $user
        )) {
            throw new BaseException(
                trans('exceptions/service/user/user.' . __FUNCTION__ . '.deletionRequest'),
                null,
                422
            );
        }

        if ($this->userUnsuspendRequestRepository->findPendingForUser(
            $user
        )) {
            throw new BaseException(
                trans('exceptions/service/user/user.' . __FUNCTION__ . '.unsuspendRequest'),
                null,
                422
            );
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function isLastProfileRequestAccepted(
        User $user
    ) : bool
    {
        $profileRequest = $this->userProfileRequestRepository->findLastForUser(
            $user
        );

        if (!$profileRequest) {
            throw new BaseException(
                trans('exceptions/service/user/user.' . __FUNCTION__ . '.found'),
                null,
                422
            );
        }

        return $profileRequest->getRequestStatus()->isAccepted();
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function checkLoginAttempts(
        User $user
    ) : bool
    {
        if (Carbon::now() > $user->next_login_attempt_at) {
            if ($user->login_attempts_left == 0) {
                $this->userRepository->updateLoginAttempts(
                    $user,
                    5
                );
            }

            return true;
        }

        return false;
    }

    /**
     * @param User $user
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function decreaseLoginAttempts(
        User $user
    ) : ?User
    {
        if ($user->login_attempts_left > 0) {
            $user = $this->userRepository->updateLoginAttempts(
                $user,
                $user->login_attempts_left - 1
            );

            if ($user->login_attempts_left == 0) {
                return null;
            }

            return $user;
        }

        return null;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function checkEmailLastChangeValid(
        User $user
    ) : bool
    {
        /**
         * Checking email last changing
         */
        if (!$user->last_email_changed_at ||
            Carbon::now()->diffInHours($user->last_email_changed_at) >= 24
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function checkEmailAttempts(
        User $user
    ) : bool
    {
        if (Carbon::now() > $user->next_email_attempt_at) {
            if ($user->email_attempts_left == 0) {
                $this->userRepository->updateEmailAttempts(
                    $user,
                    5
                );
            }

            return true;
        }

        return false;
    }

    /**
     * @param User $user
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function decreaseEmailAttempts(
        User $user
    ) : ?User
    {
        if ($user->email_attempts_left > 0) {
            $user = $this->userRepository->updateEmailAttempts(
                $user,
                $user->email_attempts_left - 1
            );

            if ($user->email_attempts_left == 0) {
                return null;
            }

            return $user;
        }

        return null;
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function checkPasswordAttempts(
        User $user
    ) : bool
    {
        if (Carbon::now() > $user->next_password_attempt_at) {
            if ($user->password_attempts_left == 0) {
                $this->userRepository->updatePasswordAttempts(
                    $user,
                    5
                );
            }

            return true;
        }

        return false;
    }

    /**
     * @param User $user
     *
     * @return User|null
     *
     * @throws DatabaseException
     */
    public function decreasePasswordAttempts(
        User $user
    ) : ?User
    {
        if ($user->password_attempts_left > 0) {
            $user = $this->userRepository->updatePasswordAttempts(
                $user,
                $user->password_attempts_left - 1
            );

            if ($user->password_attempts_left == 0) {
                return null;
            }

            return $user;
        }

        return null;
    }

    /**
     * @param string $login
     *
     * @return bool
     */
    public function checkLoginIsEmail(
        string $login
    ) : bool
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }

        return true;
    }

    /**
     * @param User $user
     * @param int $avatarId
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteAvatarId(
        User $user,
        int $avatarId
    ) : bool
    {
        if ($user->avatar_id == $avatarId) {
            $this->userRepository->setAvatarId(
                $user,
                null
            );

            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param int $backgroundId
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteBackgroundId(
        User $user,
        int $backgroundId
    ) : bool
    {
        if ($user->background_id == $backgroundId) {
            $this->userRepository->setBackgroundId(
                $user,
                null
            );

            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param int $voiceSampleId
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteVoiceSampleId(
        User $user,
        int $voiceSampleId
    ) : bool
    {
        if ($user->voice_sample_id == $voiceSampleId) {
            $this->userRepository->setVoiceSampleId(
                $user,
                null
            );

            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param array $imagesIds
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteImagesIds(
        User $user,
        array $imagesIds
    ) : bool
    {
        if (!is_null($user->images_ids)) {
            $this->userRepository->setImagesIds(
                $user,
                array_values(
                    array_diff(
                        $user->images_ids,
                        $imagesIds
                    )
                )
            );

            return true;
        }

        return false;
    }

    /**
     * @param User $user
     * @param array $videosIds
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteVideosIds(
        User $user,
        array $videosIds
    ) : bool
    {
        if (!is_null($user->videos_ids)) {
            $this->userRepository->setVideosIds(
                $user,
                array_values(
                    array_diff(
                        $user->videos_ids,
                        $videosIds
                    )
                )
            );

            return true;
        }

        return false;
    }

    /**
     * @param string $birthDate
     *
     * @return bool
     */
    public function isBirthDateAllowed(
        string $birthDate
    ) : bool
    {
        return Carbon::parse($birthDate)->age >= 13;
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getByOrderItems(
        Collection $orderItems
    ) : Collection
    {
        $users = new Collection();

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            if ($orderItem->relationLoaded('timeslot')) {
                if ($orderItem->timeslot->relationLoaded('users')) {

                    /** @var User $user */
                    foreach ($orderItem->timeslot->users as $user) {
                        $users->push(
                            $user
                        );
                    }
                }
            }

            if ($orderItem->relationLoaded('seller')) {
                $users->push(
                    $orderItem->seller
                );
            }

            if ($orderItem->relationLoaded('order')) {
                if ($orderItem->order->relationLoaded('buyer')) {
                    $users->push(
                        $orderItem->order
                            ->buyer
                    );
                }
            }
        }

        return $users;
    }

    /**
     * @param Collection $cartItems
     *
     * @return Collection
     */
    public function getByCartItems(
        Collection $cartItems
    ) : Collection
    {
        $users = new Collection();

        /** @var CartItem $cartItem */
        foreach ($cartItems as $cartItem) {
            if ($cartItem->relationLoaded('appearanceCase')) {
                $appearanceCase = $cartItem->appearanceCase;

                if ($appearanceCase->relationLoaded('vybe')) {
                    $vybe = $appearanceCase->vybe;

                    if ($vybe->relationLoaded('user')) {
                        $users->push(
                            $vybe->user
                        );
                    }
                }
            }

            if ($cartItem->relationLoaded('timeslot')) {
                $timeslot = $cartItem->timeslot;

                if ($timeslot) {
                    if ($timeslot->relationLoaded('users')) {
                        foreach ($timeslot->users as $user) {
                            $users->push(
                                $user
                            );
                        }
                    }
                }
            }
        }

        return $users->unique();
    }

    /**
     * @param Collection $users
     *
     * @return Collection
     */
    public function getByFullProfile(
        Collection $users
    ) : Collection
    {
        $responseUsers = new Collection();

        /** @var User $user */
        foreach ($users as $user) {
            if ($user->relationLoaded('subscriptions')) {

                /** @var User $subscription */
                foreach ($user->subscriptions as $subscription) {
                    $responseUsers->push(
                        $subscription
                    );
                }
            }

            if ($user->relationLoaded('subscribers')) {

                /** @var User $subscriber */
                foreach ($user->subscribers as $subscriber) {
                    $responseUsers->push(
                        $subscriber
                    );
                }
            }

            $responseUsers->push(
                $user
            );
        }

        return $responseUsers;
    }

    /**
     * @param Collection $vybes
     *
     * @return Collection
     */
    public function getByVybes(
        Collection $vybes
    ) : Collection
    {
        $users = new Collection();

        /** @var Vybe $vybe */
        foreach ($vybes as $vybe) {
            if ($vybe->relationLoaded('user')) {
                $users->push(
                    $vybe->user
                );
            }
        }

        return $users;
    }

    /**
     * @param Collection $timeslots
     *
     * @return Collection
     */
    public function getByTimeslots(
        Collection $timeslots
    ) : Collection
    {
        $users = new Collection();

        /** @var Timeslot $timeslot */
        foreach ($timeslots as $timeslot) {
            if ($timeslot->relationLoaded('users')) {

                /** @var User $user */
                foreach ($timeslot->users as $user) {
                    $users->push(
                        $user
                    );
                }
            }
        }

        return $users->unique();
    }

    /**
     * @param array $slots
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getUsersFromSlots(
        array $slots
    ) : Collection
    {
        /**
         * Preparing the users collection
         */
        $users = new Collection();

        /** @var array $slot */
        foreach ($slots as $slot) {
            if (isset($slot['schedules'])) {

                /** @var array $schedule */
                foreach ($slot['schedules'] as $schedule) {

                    if (isset($schedule['timeslot'])) {

                        $usersIds = [];

                        /** @var array $user */
                        foreach ($schedule['timeslot']['users'] as $user) {
                            if (!in_array($user['id'], $usersIds)) {
                                $usersIds[] = $user['id'];
                            }
                        }

                        /**
                         * Getting users
                         */
                        $timeslotUsers = $this->userRepository->getUsersByIds(
                            $usersIds
                        );

                        /** @var User $timeslotUser */
                        foreach ($timeslotUsers as $timeslotUser) {

                            /**
                             * Adding a user to a collection
                             */
                            $users->push(
                                $timeslotUser
                            );
                        }
                    }
                }
            }
        }

        return $users->unique();
    }

    /**
     * @param Collection $usersAvatars
     * @param array $slots
     *
     * @return array
     */
    public function updateSlotsWithAvatars(
        Collection $usersAvatars,
        array $slots
    ) : array
    {
        /**
         * @var int $slotKey
         * @var array $slot
         */
        foreach ($slots as $slotKey => $slot) {
            if (isset($slot['schedules'])) {

                /**
                 * @var int $scheduleKey
                 * @var array $schedule
                 */
                foreach ($slot['schedules'] as $scheduleKey => $schedule) {
                    if (isset($schedule['timeslot'])) {

                        /**
                         * @var int $userKey
                         * @var User $user
                         */
                        foreach ($schedule['timeslot']['users'] as $userKey => $user) {

                            /** @var UserAvatar $userAvatar */
                            $userAvatar = $usersAvatars->filter(function ($item) use ($user) {
                                return $item->id == $user['avatar_id'];
                            })->first();

                            if ($userAvatar) {
                                $slots[$slotKey]['schedules'][$scheduleKey]['timeslot']['users'][$userKey]['avatar'] = $userAvatar->toArray();
                            }
                        }
                    }
                }
            }
        }

        return $slots;
    }

    /**
     * @param User $user
     *
     * @return float
     */
    public function getTotalTaxPercent(
        User $user
    ) : float
    {
        /**
         * Preparing tax percent variable
         */
        $taxPercent = 0;

        /**
         * Checking billing relation existence
         */
        if ($user->relationLoaded('billing')) {
            $billing = $user->billing;

            /**
             * Checking country place relation existence
             */
            if ($billing->relationLoaded('countryPlace')) {
                $countryPlace = $billing->countryPlace;

                /**
                 * Checking tax rule country relation existence
                 */
                if ($countryPlace && $countryPlace->relationLoaded('taxRuleCountry')) {
                    if ($countryPlace->taxRuleCountry) {
                        $taxPercent += $countryPlace->taxRuleCountry->tax_rate;
                    }
                }
            }

            /**
             * Checking region place relation existence
             */
            if ($billing->relationLoaded('regionPlace')) {
                $regionPlace = $billing->regionPlace;

                /**
                 * Checking tax rule region relation existence
                 */
                if ($regionPlace && $regionPlace->relationLoaded('taxRuleRegion')) {
                    if ($regionPlace->taxRuleRegion) {
                        $taxPercent += $regionPlace->taxRuleRegion->tax_rate;
                    }
                }
            }
        }

        return $taxPercent;
    }

    /**
     * @param User $user
     *
     * @throws DatabaseException
     */
    public function delete(
        User $user
    ) : void
    {
        /**
         * Getting user vybes
         */
        $vybes = $this->vybeRepository->getByUser(
            $user
        );

        /** @var Vybe $vybe */
        foreach ($vybes as $vybe) {

            /**
             * Deleting vybe
             */
            $this->vybeService->delete(
                $vybe
            );
        }

        /**
         * Deleting user
         */
        $this->userRepository->delete(
            $user
        );
    }

    /**
     * @param User $user
     * @param User $subscription
     *
     * @return bool
     */
    public function isSubscription(
        User $user,
        User $subscription
    ) : bool
    {
        return $user->subscriptions()
            ->where('subscriptions.subscription_id', '=', $subscription->id)
            ->exists();
    }

    /**
     * @param User $user
     * @param User $subscriber
     *
     * @return bool
     */
    public function isSubscriber(
        User $user,
        User $subscriber
    ) : bool
    {
        return $user->subscribers()
            ->where('subscriptions.user_id', '=', $subscriber->id)
            ->exists();
    }

    /**
     * @param User $user
     * @param User $blockedUser
     *
     * @return bool
     */
    public function isBlocked(
        User $user,
        User $blockedUser
    ) : bool
    {
        return $user->blockList()
            ->where('user_block_list.blocked_user_id', '=', $blockedUser->id)
            ->exists();
    }
}
