<?php

namespace App\Http\Controllers\Api\Guest\Unit;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Unit\Interfaces\UnitTypeControllerInterface;
use App\Lists\Unit\Type\UnitTypeList;
use App\Transformers\Api\Guest\Unit\UnitTypeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class UnitTypeController
 *
 * @package App\Http\Controllers\Api\Guest\Unit
 */
final class UnitTypeController extends BaseController implements UnitTypeControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting a unit types
         */
        $unitTypeListItems = UnitTypeList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($unitTypeListItems, new UnitTypeTransformer),
            trans('validations/api/guest/unit/type/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting a unit type
         */
        $unitTypeListItem = UnitTypeList::getItem($id);

        /**
         * Checking unit type existence
         */
        if (!$unitTypeListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/unit/type/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($unitTypeListItem, new UnitTypeTransformer),
            trans('validations/api/guest/unit/type/show.result.success')
        );
    }
}
