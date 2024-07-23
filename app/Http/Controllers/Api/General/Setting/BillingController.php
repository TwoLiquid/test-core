<?php

namespace App\Http\Controllers\Api\General\Setting;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Setting\Interfaces\BillingControllerInterface;
use App\Http\Requests\Api\General\Setting\Billing\UpdateRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Repositories\Billing\BillingChangeRequestRepository;
use App\Repositories\Billing\BillingRepository;
use App\Repositories\PhoneCode\PhoneCodeRepository;
use App\Repositories\Place\CountryPlaceRepository;
use App\Repositories\Place\RegionPlaceRepository;
use App\Repositories\User\UserRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\Billing\BillingChangeRequestService;
use App\Transformers\Api\General\Setting\Billing\BillingPageTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class BillingController
 *
 * @package App\Http\Controllers\Api\General\Setting
 */
class BillingController extends BaseController implements BillingControllerInterface
{
    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var BillingRepository
     */
    protected BillingRepository $billingRepository;

    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

    /**
     * @var BillingChangeRequestService
     */
    protected BillingChangeRequestService $billingChangeRequestService;

    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * @var PhoneCodeRepository
     */
    protected PhoneCodeRepository $phoneCodeRepository;

    /**
     * @var RegionPlaceRepository
     */
    protected RegionPlaceRepository $regionPlaceRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * BillingController constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var BillingRepository billingRepository */
        $this->billingRepository = new BillingRepository();

        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();

        /** @var BillingChangeRequestService billingChangeRequestService */
        $this->billingChangeRequestService = new BillingChangeRequestService();

        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var PhoneCodeRepository phoneCodeRepository */
        $this->phoneCodeRepository = new PhoneCodeRepository();

        /** @var RegionPlaceRepository regionPlaceRepository */
        $this->regionPlaceRepository = new RegionPlaceRepository();

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
        /**
         * Getting user billing
         */
        $billing = $this->billingRepository->findByUser(
            AuthService::user()
        );

        return $this->respondWithSuccess(
            $this->transformItem([],
                new BillingPageTransformer($billing)
            )['billing_page'],
            trans('validations/api/general/setting/billing/index.result.success')
        );
    }

    /**
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting country place
         */
        $countryPlace = $this->countryPlaceRepository->findByPlaceId(
            $request->input('country_place_id')
        );

        /**
         * Getting region place
         */
        $regionPlace = $this->regionPlaceRepository->findByPlaceId(
            $request->input('region_place_id')
        );

        /**
         * Getting phone code country place
         */
        $phoneCodeCountryPlace = $request->input('phone_code_country_place_id') ? $this->countryPlaceRepository->findByPlaceId(
            $request->input('phone_code_country_place_id')
        ) : null;

        /**
         * Checking billing country has been changed
         */
        $hasChanges = !AuthService::user()->billing
            ->countryPlace
            ->is($countryPlace);

        /**
         * Updating billing
         */
        $billing = $this->billingRepository->update(
            AuthService::user()->billing,
            null,
            null,
            $phoneCodeCountryPlace,
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('city'),
            $request->input('postal_code'),
            $request->input('address'),
            $request->input('phone'),
            $request->input('business_info'),
            $request->input('company_name'),
            $request->input('vat_number')
        );

        /**
         * Checking billing country has been changed
         */
        if (!$hasChanges) {

            /**
             * Updating region place
             */
            $billing = $this->billingRepository->updateRegionPlace(
                $billing,
                $regionPlace
            );
        }

        /**
         * Checking billing change request existence
         */
        if (!$this->billingChangeRequestRepository->existsPendingForUser(
            AuthService::user()
        )) {

            /**
             * Checking billing country has been changed
             */
            if ($hasChanges) {

                /**
                 * Creating billing change request
                 */
                $this->billingChangeRequestRepository->store(
                    AuthService::user(),
                    $countryPlace,
                    $billing->countryPlace,
                    $regionPlace
                );
            }
        }

        /**
         * Releasing billing change request counter-caches
         */
        $this->adminNavbarService->releaseBillingChangeRequestCache();

        return $this->respondWithSuccess(
            $this->transformItem([],
                new BillingPageTransformer($this->billingRepository->findByUser(
                    AuthService::user()
                ))
            )['billing_page'],
            trans('validations/api/general/setting/billing/update.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function close() : JsonResponse
    {
        /**
         * Getting billing change request
         */
        $billingChangeRequest = $this->billingChangeRequestRepository->findLastForUser(
            AuthService::user()
        );

        /**
         * Checking billing change request existence
         */
        if (!$billingChangeRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/billing/close.result.error.find')
            );
        }

        /**
         * Checking billing change request status
         */
        if (!$billingChangeRequest->getRequestStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/setting/billing/close.result.error.status')
            );
        }

        /**
         * Updating billing change request
         */
        $this->billingChangeRequestRepository->updateShown(
            $billingChangeRequest,
            true
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/billing/close.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy() : JsonResponse
    {
        /**
         * Getting pending billing change request
         */
        $billingChangeRequest = $this->billingChangeRequestRepository->findPendingForUser(
            AuthService::user()
        );

        /**
         * Checking billing change request existence
         */
        if (!$billingChangeRequest) {
            return $this->respondWithError(
                trans('validations/api/general/setting/billing/destroy.result.error.find')
            );
        }

        /**
         * Updating pending billing change request status
         */
        $this->billingChangeRequestRepository->updateRequestStatus(
            $billingChangeRequest,
            RequestStatusList::getCanceledItem()
        );

        /**
         * Releasing billing change request counter-caches
         */
        $this->adminNavbarService->releaseBillingChangeRequestCache();

        return $this->respondWithSuccess([],
            trans('validations/api/general/setting/billing/destroy.result.success')
        );
    }
}
