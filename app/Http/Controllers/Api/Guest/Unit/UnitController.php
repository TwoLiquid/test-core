<?php

namespace App\Http\Controllers\Api\Guest\Unit;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Unit\Interfaces\UnitControllerInterface;
use App\Http\Requests\Api\Guest\Unit\GetByActivitiesRequest;
use App\Http\Requests\Api\Guest\Unit\IndexRequest;
use App\Repositories\Unit\UnitRepository;
use App\Transformers\Api\Guest\Unit\UnitTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UnitController
 *
 * @package App\Http\Controllers\Api\Guest\Unit
 */
final class UnitController extends BaseController implements UnitControllerInterface
{
    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * UnitController constructor
     */
    public function __construct()
    {
        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();
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
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting units with pagination
             */
            $units = $this->unitRepository->getAllNotEventPaginated(
                $request->input('page')
            );

            return $this->setPagination($units)->respondWithSuccess(
                $this->transformCollection($units, new UnitTransformer),
                trans('validations/api/guest/unit/index.result.success')
            );
        }
        
        /**
         * Getting units
         */
        $units = $this->unitRepository->getAllNotEvent();
        
        return $this->respondWithSuccess(
            $this->transformCollection($units, new UnitTransformer),
            trans('validations/api/guest/unit/index.result.success')
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
         * Getting unit
         */
        $unit = $this->unitRepository->findById($id);

        /**
         * Checking unit existence
         */
        if (!$unit) {
            return $this->respondWithError(
                trans('validations/api/guest/unit/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($unit, new UnitTransformer),
            trans('validations/api/guest/unit/show.result.success')
        );
    }

    /**
     * @param GetByActivitiesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getByActivities(
        GetByActivitiesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting units
         */
        $units = $this->unitRepository->getByActivitiesIds(
            $request->input('activities_ids')
        );

        return $this->respondWithSuccess(
            $this->transformCollection($units, new UnitTransformer),
            trans('validations/api/guest/unit/getByActivities.result.success')
        );
    }
}
