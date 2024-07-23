<?php

namespace App\Http\Controllers\Api\Admin\User\Payout;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Payout\Interfaces\PayoutMethodRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Payout\Method\Request\UpdateRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Repositories\Media\PaymentMethodImageRepository;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use App\Services\Payment\PaymentMethodService;
use App\Services\Payout\PayoutMethodRequestService;
use App\Transformers\Api\Admin\User\Payout\Method\Request\PayoutMethodRequestTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class PayoutMethodRequestController
 *
 * @package App\Http\Controllers\Api\Admin\User\PayoutMethod
 */
final class PayoutMethodRequestController extends BaseController implements PayoutMethodRequestControllerInterface
{
    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * @var PaymentMethodService
     */
    protected PaymentMethodService $paymentMethodService;

    /**
     * @var PaymentMethodImageRepository
     */
    protected PaymentMethodImageRepository $paymentMethodImageRepository;

    /**
     * @var PayoutMethodRequestRepository
     */
    protected PayoutMethodRequestRepository $payoutMethodRequestRepository;

    /**
     * @var PayoutMethodRequestService
     */
    protected PayoutMethodRequestService $payoutMethodRequestService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * PayoutMethodRequestController constructor
     */
    public function __construct()
    {
        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var PaymentMethodService paymentMethodService */
        $this->paymentMethodService = new PaymentMethodService();

        /** @var PaymentMethodImageRepository paymentMethodImageRepository */
        $this->paymentMethodImageRepository = new PaymentMethodImageRepository();

        /** @var PayoutMethodRequestRepository payoutMethodRequestRepository */
        $this->payoutMethodRequestRepository = new PayoutMethodRequestRepository();

        /** @var PayoutMethodRequestService payoutMethodRequestService */
        $this->payoutMethodRequestService = new PayoutMethodRequestService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        int $id,
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
                trans('validations/api/admin/user/payout/method/request/update.result.error.find.user')
            );
        }

        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findById(
            $request->input('method_id')
        );

        /**
         * Checking payment method existence
         */
        if (!$paymentMethod) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/request/update.result.error.find.paymentMethod')
            );
        }

        /**
         * Getting pending profile request
         */
        $payoutMethodRequest = $this->payoutMethodRequestRepository->findPendingForUser(
            $paymentMethod,
            $user
        );

        /**
         * Checking payment method request existence
         */
        if (!$payoutMethodRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/user/payout/method/request/update.result.error.find.payoutMethodRequest')
            );
        }

        /**
         * Getting request status
         */
        $requestStatusListItem = RequestStatusList::getItem(
            $request->input('request_status_id')
        );

        /**
         * Updating payout method request
         */
        $payoutMethodRequest = $this->payoutMethodRequestRepository->updateRequestStatus(
            $payoutMethodRequest,
            $requestStatusListItem
        );

        /**
         * Checking payout method request status
         */
        if ($payoutMethodRequest->getRequestStatus()->isAccepted()) {

            /**
             * Executing payout method request
             */
            $this->payoutMethodRequestService->executeRequest(
                $payoutMethodRequest
            );

            /**
             * Attaching payout method to a user
             */
            $this->userRepository->attachPayoutMethod(
                $user,
                $paymentMethod
            );

            /**
             * Updating a payout method request toast message type
             */
            $this->payoutMethodRequestRepository->updateToastMessageType(
                $payoutMethodRequest,
                ToastMessageTypeList::getAcceptedItem(),
                $request->input('toast_message_text')
            );
        } elseif ($payoutMethodRequest->getRequestStatus()->isDeclined()) {

            /**
             * Updating a payout method request toast message type
             */
            $this->payoutMethodRequestRepository->updateToastMessageType(
                $payoutMethodRequest,
                ToastMessageTypeList::getDeclinedItem(),
                $request->input('toast_message_text')
            );
        }

        /**
         * Updating processing admin
         */
        $this->payoutMethodRequestRepository->updateAdmin(
            $payoutMethodRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem($payoutMethodRequest, new PayoutMethodRequestTransformer(
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    $this->paymentMethodService->getByPayoutMethodRequests(
                        new Collection([$payoutMethodRequest])
                    )
                )
            )), trans('validations/api/admin/user/payout/method/request/update.result.success')
        );
    }
}
