<?php

namespace App\Http\Controllers\Api\General\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Vybe\Interfaces\VybeUnpublishRequestControllerInterface;
use App\Http\Requests\Api\General\Vybe\UnpublishRequest\UpdateRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Repositories\Vybe\VybeUnpublishRequestRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Vybe\VybePageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeUnpublishRequestController
 *
 * @package App\Http\Controllers\Api\General\Vybe
 */
final class VybeUnpublishRequestController extends BaseController implements VybeUnpublishRequestControllerInterface
{
    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * @var VybeUnpublishRequestRepository
     */
    protected VybeUnpublishRequestRepository $vybeUnpublishRequestRepository;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * VybeUnpublishRequestController constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

        /** @var VybeUnpublishRequestRepository vybeUnpublishRequestRepository */
        $this->vybeUnpublishRequestRepository = new VybeUnpublishRequestRepository();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
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
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/update.result.error.find')
            );
        }

        /**
         * Checking is an owner
         */
        if (!$this->vybeService->isOwner(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/update.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if (!$vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/update.result.error.completed')
            );
        }

        /**
         * Checking vybe status
         */
        if (!$vybe->getStatus()->isPublished() &&
            !$vybe->getStatus()->isPaused()
        ) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/update.result.error.status')
            );
        }

        /**
         * Checking any vybe pending request existence
         */
        if ($this->vybeService->checkIfAnyRequestExists(
            $vybe
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/update.result.error.request')
            );
        }

        /**
         * Creating vybe unpublish request
         */
        $vybeUnpublishRequest = $this->vybeUnpublishRequestRepository->store(
            $vybe,
            $request->input('message'),
            $vybe->getStatus()
        );

        /**
         * Checking vybe unpublish request existence
         */
        if (!$vybeUnpublishRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/update.result.error.create')
            );
        }

        /**
         * Checking vybe status
         */
        if ($vybe->getStatus()->isPublished()) {

            /**
             * Update vybe
             */
            $this->vybeRepository->updateStatus(
                $vybe,
                VybeStatusList::getPausedItem()
            );
        }

        /**
         * Updating vybe unpublish request
         */
        $this->vybeUnpublishRequestRepository->updateLanguage(
            $vybeUnpublishRequest,
            $vybe->user->getLanguage()
        );

        /**
         * Releasing vybe unpublish request counter-caches
         */
        $this->adminNavbarService->releaseVybeUnpublishRequestCache();

        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        return $this->respondWithSuccess(
            $this->transformItem(
                $vybe,
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/unpublishRequest/update.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function close(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/close.result.error.find.vybe')
            );
        }

        /**
         * Checking is an owner
         */
        if (!$this->vybeService->isOwner(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/close.result.error.owner')
            );
        }

        /**
         * Getting vybe unpublish request
         */
        $vybeUnpublishRequest = $this->vybeUnpublishRequestRepository->findLastForVybe(
            $vybe
        );

        /**
         * Checking vybe unpublish request existence
         */
        if (!$vybeUnpublishRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/close.result.error.find.vybeUnpublishRequest')
            );
        }

        /**
         * Checking is vybe unpublish request status
         */
        if (!$vybeUnpublishRequest->getRequestStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/close.result.error.status')
            );
        }

        /**
         * Updating vybe unpublish request
         */
        $this->vybeUnpublishRequestRepository->updateShown(
            $vybeUnpublishRequest,
            true
        );

        /**
         * Releasing vybe unpublish request counter-caches
         */
        $this->adminNavbarService->releaseVybeUnpublishRequestCache();

        return $this->respondWithSuccess(
            $this->transformItem(
                $vybe,
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/unpublishRequest/close.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function cancel(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/cancel.result.error.find.vybe')
            );
        }

        /**
         * Checking is an owner
         */
        if (!$this->vybeService->isOwner(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/cancel.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if (!$vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/cancel.result.error.completed')
            );
        }

        /**
         * Checking vybe pending unpublish request existence
         */
        if (!$vybe->unpublishRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unpublishRequest/cancel.result.error.find.unpublishRequest')
            );
        }

        /**
         * Updating vybe unpublish request
         */
        $this->vybeUnpublishRequestRepository->updateRequestStatus(
            $vybe->unpublishRequest,
            RequestStatusList::getCanceledItem()
        );

        /**
         * Releasing vybe unpublish request counter-caches
         */
        $this->adminNavbarService->releaseVybeUnpublishRequestCache();

        return $this->respondWithSuccess(
            $this->transformItem(
                $vybe,
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/unpublishRequest/cancel.result.success')
        );
    }
}
