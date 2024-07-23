<?php

namespace App\Http\Controllers\Api\Guest\Alert;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Alert\Interfaces\AlertTypeControllerInterface;
use App\Lists\Alert\Type\AlertTypeList;
use App\Transformers\Api\Guest\Alert\AlertTypeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class AlertTypeController
 *
 * @package App\Http\Controllers\Api\Guest\Alert
 */
final class AlertTypeController extends BaseController implements AlertTypeControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting an alert types
         */
        $alertTypeListItems = AlertTypeList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($alertTypeListItems, new AlertTypeTransformer),
            trans('validations/api/guest/alert/type/index.result.success')
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
         * Getting an alert type
         */
        $alertTypeListItem = AlertTypeList::getItem($id);

        /**
         * Checking an alert type existence
         */
        if (!$alertTypeListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/alert/type/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($alertTypeListItem, new AlertTypeTransformer),
            trans('validations/api/guest/alert/type/show.result.success')
        );
    }
}
