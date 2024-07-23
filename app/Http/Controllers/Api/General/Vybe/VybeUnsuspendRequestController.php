<?php

namespace App\Http\Controllers\Api\General\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Vybe\Interfaces\VybeUnsuspendRequestControllerInterface;
use App\Http\Requests\Api\General\Vybe\UnsuspendRequest\UpdateRequest;
use App\Lists\Request\Status\RequestStatusList;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Repositories\Vybe\VybeUnsuspendRequestRepository;
use App\Services\Admin\AdminNavbarService;
use App\Services\Auth\AuthService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Vybe\VybePageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeUnsuspendRequestController
 *
 * @package App\Http\Controllers\Api\General\Vybe
 */
final class VybeUnsuspendRequestController extends BaseController implements VybeUnsuspendRequestControllerInterface
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
     * @var VybeUnsuspendRequestRepository
     */
    protected VybeUnsuspendRequestRepository $vybeUnsuspendRequestRepository;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * VybeUnsuspendRequestController constructor
     */
    public function __construct()
    {
        /** @var AdminNavbarService adminNavbarService */
        $this->adminNavbarService = new AdminNavbarService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

        /** @var VybeUnsuspendRequestRepository vybeUnsuspendRequestRepository */
        $this->vybeUnsuspendRequestRepository = new VybeUnsuspendRequestRepository();

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
                trans('validations/api/general/vybe/unsuspendRequest/update.result.error.find')
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
                trans('validations/api/general/vybe/unsuspendRequest/update.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if (!$vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unsuspendRequest/update.result.error.completed')
            );
        }

        /**
         * Checking vybe status
         */
        if (!$vybe->getStatus()->isSuspended()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unsuspendRequest/update.result.error.status')
            );
        }

        /**
         * Checking any vybe pending request existence
         */
        if ($this->vybeService->checkIfAnyRequestExists(
            $vybe
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unsuspendRequest/update.result.error.request')
            );
        }

        /**
         * Creating vybe unsuspend request
         */
        $vybeUnsuspendRequest = $this->vybeUnsuspendRequestRepository->store(
            $vybe,
            $request->input('message'),
            $vybe->getStatus()
        );

        /**
         * Checking vybe unsuspend request existence
         */
        if (!$vybeUnsuspendRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unsuspendRequest/update.result.error.create')
            );
        }

        /**
         * Updating vybe unsuspend request
         */
        $this->vybeUnsuspendRequestRepository->updateLanguage(
            $vybeUnsuspendRequest,
            $vybe->user->getLanguage()
        );

        /**
         * Releasing vybe unsuspend request counter-caches
         */
        $this->adminNavbarService->releaseVybeUnsuspendRequestCache();

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
            )['vybe_page'], trans('validations/api/general/vybe/unsuspendRequest/update.result.success')
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
                trans('validations/api/general/vybe/unsuspendRequest/close.result.error.find.vybe')
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
                trans('validations/api/general/vybe/unsuspendRequest/close.result.error.owner')
            );
        }

        /**
         * Getting vybe unsuspend request
         */
        $vybeUnsuspendRequest = $this->vybeUnsuspendRequestRepository->findLastForVybe(
            $vybe
        );

        /**
         * Checking vybe unsuspend request existence
         */
        if (!$vybeUnsuspendRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unsuspendRequest/close.result.error.find.vybeUnsuspendRequest')
            );
        }

        /**
         * Checking is vybe unsuspend request status
         */
        if (!$vybeUnsuspendRequest->getRequestStatus()->isDeclined()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unsuspendRequest/close.result.error.status')
            );
        }

        /**
         * Updating vybe unsuspend request
         */
        $this->vybeUnsuspendRequestRepository->updateShown(
            $vybeUnsuspendRequest,
            true
        );

        /**
         * Releasing vybe unsuspend request counter-caches
         */
        $this->adminNavbarService->releaseVybeUnsuspendRequestCache();

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
            trans('validations/api/general/vybe/unsuspendRequest/close.result.success')
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
                trans('validations/api/general/vybe/unsuspendRequest/cancel.result.error.find.vybe')
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
                trans('validations/api/general/vybe/unsuspendRequest/cancel.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if (!$vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unsuspendRequest/cancel.result.error.completed')
            );
        }

        /**
         * Checking vybe pending unsuspend request existence
         */
        if (!$vybe->unsuspendRequest) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/unsuspendRequest/cancel.result.error.find.unsuspendRequest')
            );
        }

        /**
         * Updating vybe unsuspend request
         */
        $this->vybeUnsuspendRequestRepository->updateRequestStatus(
            $vybe->unsuspendRequest,
            RequestStatusList::getCanceledItem()
        );

        /**
         * Releasing vybe unsuspend request counter-caches
         */
        $this->adminNavbarService->releaseVybeUnsuspendRequestCache();

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
            trans('validations/api/general/vybe/unsuspendRequest/cancel.result.success')
        );
    }
}
