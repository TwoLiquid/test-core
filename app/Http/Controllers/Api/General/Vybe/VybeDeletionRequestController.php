<?php

namespace App\Http\Controllers\Api\General\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Vybe\Interfaces\VybeDeletionRequestControllerInterface;
use App\Http\Requests\Api\General\Vybe\DeletionRequest\UpdateRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeDeletionRequestRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Vybe\VybePageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeDeletionRequestController
 *
 * @package App\Http\Controllers\Api\General\Vybe
 */
final class VybeDeletionRequestController extends BaseController implements VybeDeletionRequestControllerInterface
{
    /**
     * @var AdminNavbarService
     */
    protected AdminNavbarService $adminNavbarService;

    /**
     * @var VybeDeletionRequestRepository
     */
    protected VybeDeletionRequestRepository $vybeDeletionRequestRepository;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * VybeDeletionRequestController constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var VybeDeletionRequestRepository vybeDeletionRequestRepository */
        $this->vybeDeletionRequestRepository = new VybeDeletionRequestRepository();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

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
                trans('validations/api/general/vybe/deletionRequest/update.result.error.find')
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
                trans('validations/api/general/vybe/deletionRequest/update.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if (!$vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/deletionRequest/update.result.error.completed')
            );
        }

        /**
         * Checking any vybe pending request existence
         */
        if ($this->vybeService->checkIfAnyRequestExists(
            $vybe
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/deletionRequest/update.result.error.request')
            );
        }

        /**
         * Creating vybe deletion request
         */
        $vybeDeletionRequest = $this->vybeDeletionRequestRepository->store(
            $vybe,
            $request->input('message'),
            $vybe->getStatus()
        );

        /**
         * Checking vybe deletion request existence
         */
        if (!$vybeDeletionRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/deletionRequest/update.result.error.create')
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
         * Updating vybe deletion request
         */
        $this->vybeDeletionRequestRepository->updateLanguage(
            $vybeDeletionRequest,
            $vybe->user->getLanguage()
        );

        /**
         * Releasing vybe deletion request counter-caches
         */
        $this->adminNavbarService->releaseVybeDeletionRequestCache();

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
                    )
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/deletionRequest/update.result.success')
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
                trans('validations/api/general/vybe/deletionRequest/close.result.error.find.vybe')
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
                trans('validations/api/general/vybe/deletionRequest/close.result.error.owner')
            );
        }

        /**
         * Getting vybe deletion request
         */
        $vybeDeletionRequest = $this->vybeDeletionRequestRepository->findLastForVybe(
            $vybe
        );

        /**
         * Checking vybe deletion request existence
         */
        if (!$vybeDeletionRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/deletionRequest/close.result.error.find.vybeDeletionRequest')
            );
        }

        /**
         * Checking is vybe deletion request status
         */
        if (!$vybeDeletionRequest->getRequestStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/deletionRequest/close.result.error.status')
            );
        }

        /**
         * Updating vybe deletion request
         */
        $this->vybeDeletionRequestRepository->updateShown(
            $vybeDeletionRequest,
            true
        );

        /**
         * Releasing vybe deletion request counter-caches
         */
        $this->adminNavbarService->releaseVybeDeletionRequestCache();

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
                    )
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/deletionRequest/close.result.success')
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
                trans('validations/api/general/vybe/deletionRequest/cancel.result.error.find.vybe')
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
                trans('validations/api/general/vybe/deletionRequest/cancel.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if (!$vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/deletionRequest/cancel.result.error.completed')
            );
        }

        /**
         * Checking vybe pending deletion request existence
         */
        if (!$vybe->deletionRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/deletionRequest/cancel.result.error.find.deletionRequest')
            );
        }

        /**
         * Updating vybe deletion request
         */
        $this->vybeDeletionRequestRepository->updateRequestStatus(
            $vybe->deletionRequest,
            RequestStatusList::getCanceledItem()
        );

        /**
         * Releasing vybe deletion request counter-caches
         */
        $this->adminNavbarService->releaseVybeDeletionRequestCache();

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
                    )
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/deletionRequest/cancel.result.success')
        );
    }
}
