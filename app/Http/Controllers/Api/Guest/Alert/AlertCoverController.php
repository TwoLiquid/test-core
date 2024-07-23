<?php

namespace App\Http\Controllers\Api\Guest\Alert;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Alert\Interfaces\AlertCoverControllerInterface;
use App\Lists\Alert\Cover\AlertCoverList;
use App\Transformers\Api\Guest\Alert\AlertCoverTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class AlertCoverController
 *
 * @package App\Http\Controllers\Api\Guest\Alert
 */
final class AlertCoverController extends BaseController implements AlertCoverControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting an alert covers
         */
        $alertCoverListItems = AlertCoverList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($alertCoverListItems, new AlertCoverTransformer),
            trans('validations/api/guest/alert/cover/index.result.success')
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
         * Getting an alert cover
         */
        $alertCoverListItem = AlertCoverList::getItem($id);

        /**
         * Checking an alert cover existence
         */
        if (!$alertCoverListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/alert/cover/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($alertCoverListItem, new AlertCoverTransformer),
            trans('validations/api/guest/alert/cover/show.result.success')
        );
    }
}
