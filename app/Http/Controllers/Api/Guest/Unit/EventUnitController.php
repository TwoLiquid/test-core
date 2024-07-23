<?php

namespace App\Http\Controllers\Api\Guest\Unit;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Unit\Interfaces\EventUnitControllerInterface;
use App\Http\Requests\Api\Guest\Unit\Event\IndexRequest;
use App\Repositories\Unit\UnitRepository;
use App\Transformers\Api\Guest\Unit\EventUnitTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class EventUnitController
 *
 * @package App\Http\Controllers\Api\Guest\Unit
 */
final class EventUnitController extends BaseController implements EventUnitControllerInterface
{
    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * EventUnitController constructor
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
             * Getting event units with pagination
             */
            $eventUnits = $this->unitRepository->getAllNotEventPaginated(
                $request->input('page')
            );

            return $this->setPagination($eventUnits)->respondWithSuccess(
                $this->transformCollection($eventUnits, new EventUnitTransformer),
                trans('validations/api/guest/unit/event/index.result.success')
            );
        }
        
        /**
         * Getting event units
         */
        $eventUnits = $this->unitRepository->getAllNotEvent();
        
        return $this->respondWithSuccess(
            $this->transformCollection($eventUnits, new EventUnitTransformer),
            trans('validations/api/guest/unit/event/index.result.success')
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
         * Getting event unit
         */
        $unitEvent = $this->unitRepository->findById($id);

        /**
         * Checking unit event existence
         */
        if (!$unitEvent) {
            return $this->respondWithError(
                trans('validations/api/guest/unit/event/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($unitEvent, new EventUnitTransformer),
            trans('validations/api/guest/unit/event/show.result.success')
        );
    }
}
