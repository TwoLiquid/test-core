<?php

namespace App\Http\Controllers\Api\General\Navbar;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Navbar\Interfaces\NavbarControllerInterface;
use App\Lists\Currency\CurrencyList;
use App\Lists\Language\LanguageList;
use App\Lists\User\State\Status\UserStateStatusList;
use App\Lists\User\Theme\UserThemeList;
use App\Microservices\Auth\AuthMicroservice;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Media\CategoryIconRepository;
use App\Repositories\Timezone\TimezoneRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use App\Transformers\Api\General\Navbar\NavbarTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class NavbarController
 *
 * @package App\Http\Controllers\Api\General\Navbar
 */
class NavbarController extends BaseController implements NavbarControllerInterface
{
    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var CategoryIconRepository
     */
    protected CategoryIconRepository $categoryIconRepository;

    /**
     * @var TimezoneRepository
     */
    protected TimezoneRepository $timezoneRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * NavbarController constructor
     */
    public function __construct()
    {
        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CategoryIconRepository categoryIconRepository */
        $this->categoryIconRepository = new CategoryIconRepository();

        /** @var TimezoneRepository timezoneRepository */
        $this->timezoneRepository = new TimezoneRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        return $this->respondWithSuccess(
            $this->transformItem([],
                new NavbarTransformer(
                    AuthService::user(),
                    $this->categoryIconRepository->getByCategories(
                        $this->categoryRepository->getParentCategories()
                    )
                )
            )['navbar'],
            trans('validations/api/general/navbar/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updateLanguage(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting language
         */
        $languageListItem = LanguageList::getItem($id);

        /**
         * Checking language existence
         */
        if (!$languageListItem) {
            return $this->respondWithError(
                trans('validations/api/general/navbar/updateLanguage.result.error.find')
            );
        }

        /**
         * Checking is language translatable
         */
        if (!$languageListItem->translatable) {
            return $this->respondWithError(
                trans('validations/api/general/navbar/updateLanguage.result.error.translatable')
            );
        }

        /**
         * Updating client profile language
         */
        $this->userRepository->updateLanguage(
            AuthService::user(),
            $languageListItem
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/navbar/updateLanguage.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updateCurrency(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting currency
         */
        $currencyListItem = CurrencyList::getItem($id);

        /**
         * Checking currency existence
         */
        if (!$currencyListItem) {
            return $this->respondWithError(
                trans('validations/api/general/navbar/updateCurrency.result.error.find')
            );
        }

        /**
         * Updating client profile currency
         */
        $this->userRepository->updateCurrency(
            AuthService::user(),
            $currencyListItem
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/navbar/updateCurrency.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updateStateStatus(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting user state status
         */
        $userStateStatusListItem = UserStateStatusList::getItem($id);

        /**
         * Checking user state status existence
         */
        if (!$userStateStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/general/navbar/updateStateStatus.result.error.find')
            );
        }

        /**
         * Updating user state status
         */
        $this->userRepository->updateStateStatus(
            AuthService::user(),
            $userStateStatusListItem
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/navbar/updateStateStatus.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updateTimezone(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting timezone
         */
        $timezone = $this->timezoneRepository->findById($id);

        /**
         * Checking timezone existence
         */
        if (!$timezone) {
            return $this->respondWithError(
                trans('validations/api/general/navbar/updateTimezone.result.error.find')
            );
        }

        /**
         * Updating user
         */
        $this->userRepository->updateTimezone(
            AuthService::user(),
            $timezone
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/navbar/updateTimezone.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updateTheme(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting user theme
         */
        $userThemeListItem = UserThemeList::getItem($id);

        /**
         * Checking user theme existence
         */
        if (!$userThemeListItem) {
            return $this->respondWithError(
                trans('validations/api/general/navbar/updateTheme.result.error.find')
            );
        }

        /**
         * Updating user theme
         */
        $this->userRepository->updateTheme(
            AuthService::user(),
            $userThemeListItem
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/navbar/updateTheme.result.success')
        );
    }
}
