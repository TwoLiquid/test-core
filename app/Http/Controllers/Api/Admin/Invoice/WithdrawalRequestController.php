<?php

namespace App\Http\Controllers\Api\Admin\Invoice;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Invoice\Interfaces\WithdrawalRequestControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Invoice\Withdrawal\Request\UpdateRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusList;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Receipt\WithdrawalReceiptRepository;
use App\Repositories\Receipt\WithdrawalRequestRepository;
use App\Services\Auth\AuthService;
use App\Services\Log\LogService;
use App\Transformers\Api\Admin\Invoice\Withdrawal\Request\WithdrawalRequestTransformer;
use App\Transformers\Api\Admin\Invoice\Withdrawal\Request\RequestStatusTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class WithdrawalRequestController
 *
 * @package App\Http\Controllers\Api\Admin\Invoice
 */
class WithdrawalRequestController extends BaseController implements WithdrawalRequestControllerInterface
{
    /**
     * @var LogService
     */
    protected LogService $logService;

    /**
     * @var PaymentMethodRepository
     */
    protected PaymentMethodRepository $paymentMethodRepository;

    /**
     * @var WithdrawalReceiptRepository
     */
    protected WithdrawalReceiptRepository $withdrawalReceiptRepository;

    /**
     * @var WithdrawalRequestRepository
     */
    protected WithdrawalRequestRepository $withdrawalRequestRepository;

    /**
     * WithdrawalRequestController constructor
     */
    public function __construct()
    {
        /** @var LogService logService */
        $this->logService = new LogService();

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var WithdrawalRequestRepository withdrawalReceiptRepository */
        $this->withdrawalReceiptRepository = new WithdrawalReceiptRepository();

        /** @var WithdrawalRequestRepository withdrawalRequestRepository */
        $this->withdrawalRequestRepository = new WithdrawalRequestRepository();
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        string $id
    ) : JsonResponse
    {
        /**
         * Getting withdrawal request
         */
        $withdrawalRequest = $this->withdrawalRequestRepository->findById($id);

        /**
         * Checking withdrawal request existence
         */
        if (!$withdrawalRequest) {
            $this->respondWithError(
                trans('validations/api/admin/invoice/withdrawal/request/index.result.error.find')
            );
        }

        /**
         * Getting request statuses
         */
        $requestStatusListItems = RequestStatusList::getItems();

        return $this->respondWithSuccess(
            array_merge(
                $this->transformItem($withdrawalRequest, new WithdrawalRequestTransformer),
                $this->transformCollection($requestStatusListItems, new RequestStatusTransformer)
            ), trans('validations/api/admin/invoice/withdrawal/request/index.result.success')
        );
    }

    /**
     * @param string $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function update(
        string $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting withdrawal request
         */
        $withdrawalRequest = $this->withdrawalRequestRepository->findById($id);

        /**
         * Checking withdrawal request existence
         */
        if (!$withdrawalRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/withdrawal/request/update.result.error.find')
            );
        }

        /**
         * Checking withdrawal request status
         */
        if (!$withdrawalRequest->getStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/withdrawal/request/update.result.error.status')
            );
        }

        /**
         * Getting request status
         */
        $requestStatus = RequestStatusList::getItem(
            $request->input('status_id')
        );

        /**
         * Updating withdrawal request
         */
        $withdrawalRequest = $this->withdrawalRequestRepository->updateRequestStatus(
            $withdrawalRequest,
            $requestStatus,
            $request->input('toast_message_text')
        );

        /**
         * Checking withdrawal request status
         */
        if ($withdrawalRequest->getStatus()->isAccepted()) {

            /**
             * Getting payout method
             */
            $payoutMethod = $withdrawalRequest->method;
            if ($request->input('method_id')) {
                $payoutMethod = $this->paymentMethodRepository->findById(
                    $request->input('method_id')
                );
            }

            /**
             * Creating withdrawal receipt
             */
            $withdrawalReceipt = $this->withdrawalReceiptRepository->store(
                $withdrawalRequest->user,
                $payoutMethod,
                WithdrawalReceiptStatusList::getUnpaid(),
                null,
                $withdrawalRequest->amount,
                null,
                null
            );

            /**
             * Checking withdrawal receipt existence
             */
            if ($withdrawalReceipt) {

                /**
                 * Updating withdrawal request receipt
                 */
                $withdrawalRequest = $this->withdrawalRequestRepository->updateReceipt(
                    $withdrawalRequest,
                    $withdrawalReceipt
                );

                try {

                    /**
                     * Creating withdrawal request log
                     */
                    $this->logService->addWithdrawalRequestLog(
                        $withdrawalRequest,
                        $withdrawalRequest->user->getSellerBalance(),
                        UserBalanceTypeList::getSeller(),
                        'accepted'
                    );
                } catch (Exception $exception) {

                    /**
                     * Adding background error to controller stack
                     */
                    $this->addBackgroundError(
                        $exception
                    );
                }

                try {

                    /**
                     * Creating withdrawal receipt log
                     */
                    $this->logService->addWithdrawalReceiptLog(
                        $withdrawalReceipt,
                        $withdrawalReceipt->user->getSellerBalance(),
                        UserBalanceTypeList::getSeller(),
                        'paid'
                    );
                } catch (Exception $exception) {

                    /**
                     * Adding background error to controller stack
                     */
                    $this->addBackgroundError(
                        $exception
                    );
                }
            }
        } elseif ($withdrawalRequest->getStatus()->isDeclined()) {

            try {

                /**
                 * Creating withdrawal request log
                 */
                $this->logService->addWithdrawalRequestLog(
                    $withdrawalRequest,
                    $withdrawalRequest->user->getSellerBalance(),
                    UserBalanceTypeList::getSeller(),
                    'declined'
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Updating processing admin
         */
        $this->withdrawalRequestRepository->updateAdmin(
            $withdrawalRequest,
            AuthService::admin()
        );

        return $this->respondWithSuccess(
            $this->transformItem($withdrawalRequest, new WithdrawalRequestTransformer),
            trans('validations/api/admin/invoice/withdrawal/request/update.result.success')
        );
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function resendEmail(
        string $id
    ) : JsonResponse
    {
        /**
         * Getting withdrawal request
         */
        $withdrawalRequest = $this->withdrawalRequestRepository->findById($id);

        /**
         * Checking withdrawal request existence
         */
        if (!$withdrawalRequest) {
            return $this->respondWithError(
                trans('validations/api/admin/invoice/withdrawal/request/resendEmail.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($withdrawalRequest, new WithdrawalRequestTransformer),
            trans('validations/api/admin/invoice/withdrawal/request/resendEmail.result.success')
        );
    }
}
