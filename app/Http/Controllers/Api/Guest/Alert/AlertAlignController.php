<?php

namespace App\Http\Controllers\Api\Guest\Alert;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Alert\Interfaces\AlertAlignControllerInterface;
use App\Lists\Alert\Align\AlertAlignList;
use App\Transformers\Api\Guest\Alert\AlertAlignTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class AlertAlignController
 *
 * @package App\Http\Controllers\Api\Guest\Alert
 */
final class AlertAlignController extends BaseController implements AlertAlignControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting an alert aligns
         */
        $alertAlignListItems = AlertAlignList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($alertAlignListItems, new AlertAlignTransformer),
            trans('validations/api/guest/alert/align/index.result.success')
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
         * Getting an alert align
         */
        $alertAlignListItem = AlertAlignList::getItem($id);

        /**
         * Checking an alert align existence
         */
        if (!$alertAlignListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/alert/align/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($alertAlignListItem, new AlertAlignTransformer),
            trans('validations/api/guest/alert/align/show.result.success')
        );
    }
}
