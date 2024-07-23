<?php

namespace App\Http\Controllers\Api\Admin\General\Payment;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\General\Payment\Interfaces\PaymentMethodControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\General\Payment\Method\IndexRequest;
use App\Http\Requests\Api\Admin\General\Payment\Method\StoreRequest;
use App\Http\Requests\Api\Admin\General\Payment\Method\UpdateRequest;
use App\Lists\Payment\Method\Payment\Status\PaymentStatusList;
use App\Lists\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusList;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Media\PaymentMethodImageRepository;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Place\CountryPlaceRepository;
use App\Services\Payment\PaymentMethodService;
use App\Transformers\Api\Admin\General\Payment\Form\FormTransformer;
use App\Transformers\Api\Admin\General\Payment\PaymentMethodListPageTransformer;
use App\Transformers\Api\Admin\General\Payment\PaymentMethodPageTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class PaymentMethodController
 *
 * @package App\Http\Controllers\Api\Admin\General\Payment
 */
final class PaymentMethodController extends BaseController implements PaymentMethodControllerInterface
{
    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

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
     * PaymentMethodController constructor
     */
    public function __construct()
    {
        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var MediaMicroservice mediaMicroservice */

        /** @var PaymentMethodRepository paymentMethodRepository */
        $this->paymentMethodRepository = new PaymentMethodRepository();

        /** @var PaymentMethodService paymentMethodService */
        $this->paymentMethodService = new PaymentMethodService();

        /** @var PaymentMethodImageRepository paymentMethodImageRepository */
        $this->paymentMethodImageRepository = new PaymentMethodImageRepository();
    }

    /**
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Checking pagination exists
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting payment methods with pagination
             */
            $paymentMethods = $this->paymentMethodRepository->getAllForAdminPaginated(
                $request->input('page')
            );

            return $this->setPagination($paymentMethods)->respondWithSuccess(
                $this->transformItem([],
                    new PaymentMethodListPageTransformer(
                        new Collection($paymentMethods->items()),
                        $this->paymentMethodImageRepository->getByPaymentMethods(
                            new Collection($paymentMethods->items())
                        )
                    )
                )['payment_method_list_page'],
                trans('validations/api/admin/general/payment/method/index.result.success')
            );
        }

        /**
         * Getting payment methods
         */
        $paymentMethods = $this->paymentMethodRepository->getAllForAdmin();

        return $this->respondWithSuccess(
            $this->transformItem($paymentMethods, new PaymentMethodListPageTransformer(
                $paymentMethods,
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    $paymentMethods
                )
            ))['payment_method_list_page'],
            trans('validations/api/admin/general/payment/method/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findFullById($id);

        /**
         * Checking payment method existence
         */
        if (!$paymentMethod) {
            return $this->respondWithError(
                trans('validations/api/admin/general/payment/method/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($paymentMethod, new PaymentMethodPageTransformer(
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    new Collection([$paymentMethod])
                )
            ))['payment_method_page'],
            trans('validations/api/admin/general/payment/method/show.result.success')
        );
    }

    /**
     * @return JsonResponse
     */
    public function getForm() : JsonResponse
    {
        return $this->respondWithSuccess(
            $this->transformItem([],
                new FormTransformer
            ), trans('validations/api/admin/general/payment/method/getForm.result.success')
        );
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Getting payment status
         */
        $paymentStatusListItem = PaymentStatusList::getItem(
            $request->input('payment_status_id')
        );

        /**
         * Getting payment method withdrawal status
         */
        $paymentMethodWithdrawalStatusListItem = PaymentMethodWithdrawalStatusList::getItem(
            $request->input('withdrawal_status_id')
        );

        /**
         * Creating payment method
         */
        $paymentMethod = $this->paymentMethodRepository->store(
            $paymentStatusListItem,
            $paymentMethodWithdrawalStatusListItem,
            $request->input('name'),
            generateCodeByName($request->input('name')),
            $request->input('payment_fee'),
            $request->input('order_form'),
            $request->input('display_name'),
            $request->input('duration_title'),
            $request->input('duration_amount'),
            $request->input('fee_title'),
            $request->input('fee_amount'),
            $request->input('integrated')
        );

        /**
         * Checking payment method existence
         */
        if (!$paymentMethod) {
            return $this->respondWithError(
                trans('validations/api/admin/general/payment/method/store.result.error.create')
            );
        }

        /**
         * Checking country places ids existence
         */
        if ($request->input('country_places_ids')) {

            /**
             * Getting country places
             */
            $countryPlaces = $this->countryPlaceRepository->getByPlaceIds(
                $request->input('country_places_ids')
            );

            /**
             * Updating payment method country places
             */
            $this->paymentMethodService->updateCountryPlaces(
                $paymentMethod,
                $countryPlaces
            );
        }

        /**
         * Checking excluded country places ids existence
         */
        if ($request->input('excluded_country_places_ids')) {

            /**
             * Getting country places
             */
            $excludedCountryPlaces = $this->countryPlaceRepository->getByPlaceIds(
                $request->input('excluded_country_places_ids')
            );

            /**
             * Updating payment method country places
             */
            $this->paymentMethodService->updateCountryPlaces(
                $paymentMethod,
                $excludedCountryPlaces,
                true
            );
        }

        /**
         * Creating payment method fields
         */
        $this->paymentMethodService->createFields(
            $paymentMethod,
            $request->input('fields')
        );

        /**
         * Checking payment method image existence
         */
        if ($request->input('image')) {

            /**
             * Creating payment method image
             */
            $this->mediaMicroservice->storePaymentMethodImage(
                $paymentMethod,
                $request->input('image')['content'],
                $request->input('image')['mime'],
                $request->input('image')['extension']
            );
        }

        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findFullById(
            $paymentMethod->id
        );

        return $this->respondWithSuccess(
            $this->transformItem($paymentMethod, new PaymentMethodPageTransformer(
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    new Collection([$paymentMethod])
                )
            ))['payment_method_page'],
            trans('validations/api/admin/general/payment/method/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findFullById($id);

        /**
         * Checking payment method existence
         */
        if (!$paymentMethod) {
            return $this->respondWithError(
                trans('validations/api/admin/general/payment/method/update.result.error.find')
            );
        }

        /**
         * Getting payment status
         */
        $paymentStatusListItem = PaymentStatusList::getItem(
            $request->input('payment_status_id')
        );

        /**
         * Getting payment method withdrawal status
         */
        $paymentMethodWithdrawalStatusListItem = PaymentMethodWithdrawalStatusList::getItem(
            $request->input('withdrawal_status_id')
        );

        /**
         * Creating payment method
         */
        $paymentMethod = $this->paymentMethodRepository->update(
            $paymentMethod,
            $paymentStatusListItem,
            $paymentMethodWithdrawalStatusListItem,
            $request->input('name'),
            generateCodeByName($request->input('name')),
            $request->input('payment_fee'),
            $request->input('order_form'),
            $request->input('display_name'),
            $request->input('duration_title'),
            $request->input('duration_amount'),
            $request->input('fee_title'),
            $request->input('fee_amount'),
            $request->input('integrated')
        );

        /**
         * Checking country places ids existence
         */
        if ($request->input('country_places_ids')) {

            /**
             * Getting country places
             */
            $countryPlaces = $this->countryPlaceRepository->getByPlaceIds(
                $request->input('country_places_ids')
            );

            /**
             * Updating payment method country places
             */
            $this->paymentMethodService->updateCountryPlaces(
                $paymentMethod,
                $countryPlaces
            );
        }

        /**
         * Checking excluded country places ids existence
         */
        if ($request->input('excluded_country_places_ids')) {

            /**
             * Getting country places
             */
            $countryPlaces = $this->countryPlaceRepository->getByPlaceIds(
                $request->input('excluded_country_places_ids')
            );

            /**
             * Updating payment method country places
             */
            $this->paymentMethodService->updateCountryPlaces(
                $paymentMethod,
                $countryPlaces,
                true
            );
        }

        /**
         * Updating payment method fields
         */
        $this->paymentMethodService->updateFields(
            $paymentMethod,
            $request->input('fields')
        );

        /**
         * Checking payment method image existence
         */
        if ($request->input('image')) {

            /**
             * Deleting payment method image
             */
            $this->mediaMicroservice->deleteForPaymentMethod(
                $paymentMethod
            );

            /**
             * Creating payment method image
             */
            $this->mediaMicroservice->storePaymentMethodImage(
                $paymentMethod,
                $request->input('image')['content'],
                $request->input('image')['mime'],
                $request->input('image')['extension']
            );
        }

        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findFullById($id);

        return $this->respondWithSuccess(
            $this->transformItem($paymentMethod, new PaymentMethodPageTransformer(
                $this->paymentMethodImageRepository->getByPaymentMethods(
                    new Collection([$paymentMethod])
                )
            ))['payment_method_page'],
            trans('validations/api/admin/general/payment/method/update.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function destroy(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting payment method
         */
        $paymentMethod = $this->paymentMethodRepository->findFullById($id);

        /**
         * Checking payment method existence
         */
        if (!$paymentMethod) {
            return $this->respondWithError(
                trans('validations/api/admin/general/payment/method/update.result.error.find')
            );
        }

        /**
         * Deleting payment method image
         */
        $this->mediaMicroservice->deleteForPaymentMethod(
            $paymentMethod
        );

        /**
         * Deleting payment method
         */
        $this->paymentMethodRepository->delete(
            $paymentMethod
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/payment/method/destroy.result.success')
        );
    }
}
