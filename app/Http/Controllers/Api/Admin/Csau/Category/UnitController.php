<?php

namespace App\Http\Controllers\Api\Admin\Csau\Category;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\Csau\Category\Interfaces\UnitControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Csau\Category\Unit\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Unit\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Unit\UpdatePositionsRequest;
use App\Http\Requests\Api\Admin\Csau\Category\Unit\UpdateRequest;
use App\Lists\Unit\Type\UnitTypeList;
use App\Microservices\Auth\AuthMicroservice;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Unit\UnitRepository;
use App\Services\Auth\AuthService;
use App\Services\Unit\UnitService;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\Csau\Category\UnitTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

/**
 * Class UnitController
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Category
 */
final class UnitController extends BaseController implements UnitControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * @var UnitService
     */
    protected UnitService $unitService;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * UnitController constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();

        /** @var UnitService unitService */
        $this->unitService = new UnitService();

        /** @var UserService userService */
        $this->userService = new UserService();
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById(
            $request->input('activity_id')
        );

        /**
         * Creating unit
         */
        $unit = $this->unitRepository->store(
            UnitTypeList::getUsual(),
            $request->input('name'),
            $request->input('duration'),
            $request->input('visible')
        );

        /**
         * Checking unit existence
         */
        if (!$unit) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/unit/store.result.error.create')
            );
        }

        if ($activity) {
            $this->activityRepository->attachUnit(
                $activity,
                $unit
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($unit, new UnitTransformer),
            trans('validations/api/admin/csau/category/unit/store.result.success')
        );
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
         * Getting activity
         */
        $unit = $this->unitRepository->findById($id);

        if (!$unit) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/unit/update.result.error.find')
            );
        }

        /**
         * Updating unit
         */
        $unit = $this->unitRepository->update(
            $unit,
            null,
            $request->input('name'),
            $request->input('duration'),
            $request->input('visible')
        );

        return $this->respondWithSuccess(
            $this->transformItem($unit, new UnitTransformer),
            trans('validations/api/admin/csau/category/unit/store.result.success')
        );
    }

    /**
     * @param UpdatePositionsRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updatePositions(
        UpdatePositionsRequest $request
    ) : JsonResponse
    {
        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById(
            $request->input('activity_id')
        );

        /**
         * Checking activity existence
         */
        if (!$activity) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/unit/updatePositions.result.error.activity.find')
            );
        }

        /**
         * Checking units belonging to activity
         */
        if (!$this->unitService->belongsToActivity(
            $activity,
            $request->input('units_items')
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/unit/updatePositions.result.error.units.belonging')
            );
        }

        /**
         * Updating categories positions
         */
        $units = $this->unitService->updateActivityPositions(
            $activity,
            $request->input('units_items')
        );

        return $this->respondWithSuccess(
            $this->transformCollection($units, new UnitTransformer),
            trans('validations/api/admin/csau/category/unit/updatePositions.result.success')
        );
    }

    /**
     * @param int $id
     * @param DestroyRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function destroy(
        int $id,
        DestroyRequest $request
    ) : JsonResponse
    {
        /**
         * Checking admin has super rights
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/unit/destroy.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting unit
         */
        $unit = $this->unitRepository->findById($id);

        /**
         * Checking unit existence
         */
        if (!$unit) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/unit/destroy.result.error.find')
            );
        }

        /**
         * Deleting unit
         */
        if (!$this->unitRepository->delete($unit)) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/category/unit/destroy.result.error.delete')
            );
        }

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/category/unit/destroy.result.success')
        );
    }
}
