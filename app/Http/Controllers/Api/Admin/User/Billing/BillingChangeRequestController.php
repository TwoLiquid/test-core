<?php

namespace App\Http\Controllers\Api\Admin\User\Billing;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Billing\Interfaces\BillingChangeRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Billing\Request\UpdateRequest;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Status\RequestStatusList;
use App\Repositories\Billing\BillingChangeRequestRepository;
use App\Repositories\Billing\BillingRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\VatNumberProof\VatNumberProofRepository;
use App\Services\Auth\AuthService;
use App\Services\Billing\BillingService;
use App\Services\Notification\EmailNotificationService;
use App\Services\VatNumberProof\VatNumberProofService;
use App\Transformers\Api\Admin\User\Billing\BillingChangeRequestTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class BillingChangeRequestController
 *
 * @package App\Http\Controllers\Api\Admin\User\Billing
 */
final class BillingChangeRequestController extends BaseController implements BillingChangeRequestControllerInterface
{
    /**
     * @var BillingRepository
     */
    protected BillingRepository $billingRepository;

    /**
     * @var BillingService
     */
    protected BillingService $billingService;

    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

    /**
     * @var EmailNotificationService
     */
    protected EmailNotificationService $emailNotificationService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var VatNumberProofRepository
     */
    protected VatNumberProofRepository $vatNumberProofRepository;

    /**
     * @var VatNumberProofService
     */
    protected VatNumberProofService $vatNumberProofService;

    /**
     * BillingChangeRequestController constructor
     */
    public function __construct()
    {
        /** @var BillingRepository billingRepository */
        $this->billingRepository = new BillingRepository();

        /** @var BillingService billingService */
        $this->billingService = new BillingService();

        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();

        /** @var EmailNotificationService emailNotificationService */
        $this->emailNotificationService = new EmailNotificationService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var VatNumberProofRepository vatNumberProofRepository */
        $this->vatNumberProofRepository = new VatNumberProofRepository();

        /** @var VatNumberProofService vatNumberProofService */
        $this->vatNumberProofService = new VatNumberProofService();
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/billing/changeRequest/index.result.error.find.user')
            );
        }

        /**
         * Getting pending billing change request
         */
        $billingChangeRequest = $this->billingChangeRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking pending billing change request existence
         */
        if (!$billingChangeRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/billing/changeRequest/index.result.error.find.billingChangeRequest')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($billingChangeRequest, new BillingChangeRequestTransformer),
            trans('validations/api/admin/user/billing/changeRequest/index.result.success')
        );
    }

    /**
     * @param string $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        string $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/billing/changeRequest/update.result.error.find.user')
            );
        }

        /**
         * Getting pending billing change request
         */
        $billingChangeRequest = $this->billingChangeRequestRepository->findPendingForUser(
            $user
        );

        /**
         * Checking billing change request existence
         */
        if (!$billingChangeRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/billing/changeRequest/update.result.error.find.billingChangeRequest')
            );
        }

        /**
         * Getting billing change request data
         */
        $countryStatusListItem = RequestFieldStatusList::getItem(
            $request->input('country_place_status_id')
        );

        /**
         * Updating billing change request
         */
        $billingChangeRequest = $this->billingChangeRequestRepository->updateStatuses(
            $billingChangeRequest,
            $countryStatusListItem,
            $request->input('toast_message_text')
        );

        /**
         * Checkin billing change request country place status
         */
        if ($billingChangeRequest->getCountryPlaceStatus()->isAccepted()) {

            /**
             * Updating billing change request status
             */
            $this->billingChangeRequestRepository->updateRequestStatus(
                $billingChangeRequest,
                RequestStatusList::getAcceptedItem()
            );

            /**
             * Updating billing
             */
            $this->billingRepository->updatePlaces(
                $user->billing,
                $billingChangeRequest->countryPlace,
                $billingChangeRequest->regionPlace
            );

            /**
             * Sending email notification to a user
             */
            $this->emailNotificationService->sendBillingChangeAccepted(
                $user
            );
        } else {

            /**
             * Updating billing change request status
             */
            $this->billingChangeRequestRepository->updateRequestStatus(
                $billingChangeRequest,
                RequestStatusList::getDeclinedItem()
            );

            /**
             * Sending email notification to a user
             */
            $this->emailNotificationService->sendBillingChangeDeclined(
                $user
            );
        }

        /**
         * Updating billing change request processing admin
         */
        $this->billingChangeRequestRepository->updateAdmin(
            $billingChangeRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem($billingChangeRequest, new BillingChangeRequestTransformer),
            trans('validations/api/admin/user/billing/changeRequest/update.result.success')
        );
    }
}
