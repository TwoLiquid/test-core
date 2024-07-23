<?php

namespace App\Http\Controllers\Api\Admin\Csau\Suggestion;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Csau\Suggestion\Interfaces\DeviceControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Csau\Suggestion\Device\IndexRequest;
use App\Http\Requests\Api\Admin\Csau\Suggestion\Device\UpdateRequest;
use App\Lists\Request\Field\Status\RequestFieldStatusList;
use App\Lists\Request\Status\RequestStatusList;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Suggestion\DeviceSuggestionRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Vybe\VybeChangeRequestService;
use App\Services\Vybe\VybePublishRequestService;
use App\Transformers\Api\Admin\Csau\Suggestion\DeviceSuggestionTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class DeviceController
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Suggestion
 */
final class DeviceController extends BaseController implements DeviceControllerInterface
{
    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * @var DeviceSuggestionRepository
     */
    protected DeviceSuggestionRepository $deviceSuggestionRepository;

    /**
     * @var VybeChangeRequestService
     */
    protected VybeChangeRequestService $vybeChangeRequestService;

    /**
     * @var VybePublishRequestService
     */
    protected VybePublishRequestService $vybePublishRequestService;

    /**
     * DeviceController constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();

        /** @var DeviceSuggestionRepository deviceSuggestionRepository */
        $this->deviceSuggestionRepository = new DeviceSuggestionRepository();

        /** @var VybeChangeRequestService vybeChangeRequestService */
        $this->vybeChangeRequestService = new VybeChangeRequestService();

        /** @var VybePublishRequestService vybePublishRequestService */
        $this->vybePublishRequestService = new VybePublishRequestService();
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
         * Checking paginated enabled
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting device suggestions
             */
            $deviceSuggestions = $this->deviceSuggestionRepository->getAllPendingPaginated(
                $request->input('date_from'),
                $request->input('date_to'),
                $request->input('username'),
                $request->input('vybe_version'),
                $request->input('vybe_title'),
                $request->input('device_name'),
                $request->input('page'),
                $request->input('per_page')
            );

            return $this->setPagination($deviceSuggestions)->respondWithSuccess(
                $this->transformCollection($deviceSuggestions, new DeviceSuggestionTransformer),
                trans('validations/api/admin/csau/suggestion/device/index.result.success')
            );
        }

        /**
         * Getting device suggestions
         */
        $deviceSuggestions = $this->deviceSuggestionRepository->getAllPending(
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('username'),
            $request->input('vybe_version'),
            $request->input('vybe_title'),
            $request->input('device_name'),
        );

        return $this->respondWithSuccess(
            $this->transformCollection($deviceSuggestions, new DeviceSuggestionTransformer),
            trans('validations/api/admin/csau/suggestion/device/index.result.success')
        );
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        string $id
    ) : JsonResponse
    {
        /**
         * Getting device suggestion
         */
        $deviceSuggestion = $this->deviceSuggestionRepository->findById($id);

        if (!$deviceSuggestion) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/device/show.result.error.find')
            );
        }

        /**
         * Checking is a device suggestion pending
         */
        if (!$deviceSuggestion->getStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/device/show.result.error.processed')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($deviceSuggestion, new DeviceSuggestionTransformer),
            trans('validations/api/admin/csau/suggestion/device/show.result.success')
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
         * Getting device suggestion
         */
        $deviceSuggestion = $this->deviceSuggestionRepository->findById($id);

        if (!$deviceSuggestion) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/device/update.result.error.find')
            );
        }

        /**
         * Checking is a device suggestion pending
         */
        if (!$deviceSuggestion->getStatus()->isPending()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/suggestion/device/update.result.error.processed')
            );
        }

        /**
         * Getting device suggestion status
         */
        $statusListItem = RequestFieldStatusList::getItem(
            $request->input('status_id')
        );

        if ($statusListItem->isAccepted()) {

            /**
             * Getting actual device suggestion
             */
            $suggestion = $request->input('suggestion') ?
                $request->input('suggestion') :
                $deviceSuggestion->suggestion;

            /**
             * Creating device
             */
            $device = $this->deviceRepository->store(
                $suggestion,
                $request->input('visible')
            );

            if (!$device) {
                return $this->respondWithError(
                    trans('validations/api/admin/csau/suggestion/device/update.result.error.create')
                );
            }

            /**
             * Updating device suggestion
             */
            $deviceSuggestion = $this->deviceSuggestionRepository->update(
                $deviceSuggestion,
                $device,
                $suggestion,
                $request->input('visible')
            );

            /**
             * Updating device suggestion status
             */
            $deviceSuggestion = $this->deviceSuggestionRepository->updateStatus(
                $deviceSuggestion,
                RequestStatusList::getAcceptedItem()
            );
        } else {

            /**
             * Updating device suggestion status
             */
            $deviceSuggestion = $this->deviceSuggestionRepository->updateStatus(
                $deviceSuggestion,
                RequestStatusList::getDeclinedItem()
            );
        }

        /**
         * Checking device suggestion vybe publish request existence
         */
        if ($deviceSuggestion->vybePublishRequest) {

            /**
             * Update vybe publish request with a device suggestion
             */
            $this->vybePublishRequestService->updateByDeviceSuggestion(
                $deviceSuggestion->vybePublishRequest,
                $deviceSuggestion
            );
        } elseif ($deviceSuggestion->vybeChangeRequest) {

            /**
             * Update vybe change request with device suggestion
             */
            $this->vybeChangeRequestService->updateByDeviceSuggestion(
                $deviceSuggestion->vybeChangeRequest,
                $deviceSuggestion
            );
        }

        /**
         * Releasing all suggestion caches
         */
        $this->adminNavbarService->releaseAllSuggestionCache();

        return $this->respondWithSuccess(
            $this->transformItem($deviceSuggestion, new DeviceSuggestionTransformer),
            trans('validations/api/admin/csau/suggestion/device/update.result.success')
        );
    }
}
