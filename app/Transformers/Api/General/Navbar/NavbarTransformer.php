<?php

namespace App\Transformers\Api\General\Navbar;

use App\Exceptions\DatabaseException;
use App\Lists\Currency\CurrencyList;
use App\Lists\Language\LanguageList;
use App\Models\MySql\User\User;
use App\Repositories\Billing\BillingChangeRequestRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Timezone\TimezoneRepository;
use App\Repositories\User\UserDeactivationRequestRepository;
use App\Repositories\User\UserDeletionRequestRepository;
use App\Repositories\User\UserIdVerificationRequestRepository;
use App\Repositories\User\UserProfileRequestRepository;
use App\Repositories\User\UserUnsuspendRequestRepository;
use App\Transformers\Api\General\Navbar\Request\UserDeletionRequestTransformer;
use App\Transformers\Api\General\Navbar\Request\UserDeactivationRequestTransformer;
use App\Transformers\Api\General\Navbar\Request\UserIdVerificationRequestTransformer;
use App\Transformers\Api\General\Navbar\Request\UserProfileRequestTransformer;
use App\Transformers\Api\General\Navbar\Request\UserUnsuspendRequestTransformer;
use App\Transformers\Api\General\Navbar\Request\BillingChangeRequestTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class NavbarTransformer
 *
 * @package App\Transformers\Api\General\Navbar
 */
class NavbarTransformer extends BaseTransformer
{
    /**
     * @var User
     */
    protected User $authUser;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

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
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

    /**
     * @var TimezoneRepository
     */
    protected TimezoneRepository $timezoneRepository;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $categoryIcons;

    /**
     * NavbarFormTransformer constructor
     *
     * @param User $user
     * @param EloquentCollection|null $categoryIcons
     */
    public function __construct(
        User $user,
        EloquentCollection $categoryIcons = null
    )
    {
        /** @var User authUser */
        $this->authUser = $user;

        /** @var EloquentCollection categoryIcons */
        $this->categoryIcons = $categoryIcons;

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();

        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();

        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();

        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();

        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();

        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();

        /** @var TimezoneRepository timezoneRepository */
        $this->timezoneRepository = new TimezoneRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'user_profile_request',
        'billing_change_request',
        'user_id_verification_request',
        'user_deactivation_request',
        'user_unsuspend_request',
        'user_deletion_request'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item
     */
    public function includeForm() : Item
    {
        return $this->item([], new FormTransformer(
            $this->categoryIcons
        ));
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeCategories() : ?Collection
    {
        $categories = $this->categoryRepository->getAll();

        return $this->collection(
            $categories,
            new CategoryTransformer(
                $this->categoryIcons
            )
        );
    }

    /**
     * @return Collection|null
     */
    public function includeLanguages() : ?Collection
    {
        $languages = LanguageList::getTranslatableItems();

        return $this->collection($languages, new LanguageTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeCurrencies() : ?Collection
    {
        $currencies = CurrencyList::getItems();

        return $this->collection($currencies, new CurrencyTransformer);
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeTimezones() : ?Collection
    {
        $timezones = $this->timezoneRepository->getAll();

        return $this->collection($timezones, new TimezoneTransformer);
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserProfileRequest() : ?Item
    {
        $userProfileRequest = $this->userProfileRequestRepository->findLastForUser(
            $this->authUser
        );

        if ($userProfileRequest && (
                $userProfileRequest->getRequestStatus()->isPending() ||
                $userProfileRequest->getRequestStatus()->isDeclined())
        ) {
            if (!$userProfileRequest->shown) {
                return $this->item($userProfileRequest, new UserProfileRequestTransformer);
            }
        }

        return null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeBillingChangeRequest() : ?Item
    {
        $billingChangeRequest = $this->billingChangeRequestRepository->findLastForUser(
            $this->authUser
        );

        if ($billingChangeRequest && (
            $billingChangeRequest->getRequestStatus()->isPending() ||
            $billingChangeRequest->getRequestStatus()->isDeclined())
        ) {
            if (!$billingChangeRequest->shown) {
                return $this->item($billingChangeRequest, new BillingChangeRequestTransformer);
            }
        }

        return null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserIdVerificationRequest() : ?Item
    {
        $userIdVerificationRequest = $this->userIdVerificationRequestRepository->findLastForUser(
            $this->authUser
        );

        if ($userIdVerificationRequest && (
            $userIdVerificationRequest->getRequestStatus()->isPending() ||
            $userIdVerificationRequest->getRequestStatus()->isDeclined())
        ) {
            if (!$userIdVerificationRequest->shown) {
                return $this->item($userIdVerificationRequest, new UserIdVerificationRequestTransformer);
            }
        }

        return null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserDeactivationRequest() : ?Item
    {
        $userDeactivationRequest = $this->userDeactivationRequestRepository->findLastForUser(
            $this->authUser
        );

        if ($userDeactivationRequest && (
            $userDeactivationRequest->getRequestStatus()->isPending() ||
            $userDeactivationRequest->getRequestStatus()->isDeclined())
        ) {
            if (!$userDeactivationRequest->shown) {
                return $this->item($userDeactivationRequest, new UserDeactivationRequestTransformer);
            }
        }

        return null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserUnsuspendRequest() : ?Item
    {
        $userUnsuspendRequest = $this->userUnsuspendRequestRepository->findLastForUser(
            $this->authUser
        );

        if ($userUnsuspendRequest && (
            $userUnsuspendRequest->getRequestStatus()->isPending() ||
            $userUnsuspendRequest->getRequestStatus()->isDeclined())
        ) {
            if (!$userUnsuspendRequest->shown) {
                return $this->item($userUnsuspendRequest, new UserUnsuspendRequestTransformer);
            }
        }

        return null;
    }

    /**
     * @return Item|null
     *
     * @throws DatabaseException
     */
    public function includeUserDeletionRequest() : ?Item
    {
        $userDeletionRequest = $this->userDeletionRequestRepository->findLastForUser(
            $this->authUser
        );

        if ($userDeletionRequest && (
            $userDeletionRequest->getRequestStatus()->isPending() ||
            $userDeletionRequest->getRequestStatus()->isDeclined())
        ) {
            if (!$userDeletionRequest->shown) {
                return $this->item($userDeletionRequest, new UserDeletionRequestTransformer);
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'navbar';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'navbars';
    }
}
