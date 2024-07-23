<?php

namespace App\Http\Controllers\Api\Guest\Alert;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Alert\Interfaces\AlertTextStyleControllerInterface;
use App\Lists\Alert\Text\Style\AlertTextStyleList;
use App\Transformers\Api\Guest\Alert\AlertTextStyleTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class AlertTextStyleController
 *
 * @package App\Http\Controllers\Api\Guest\Alert
 */
final class AlertTextStyleController extends BaseController implements AlertTextStyleControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting an alert text styles
         */
        $alertTextStyleListItems = AlertTextStyleList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($alertTextStyleListItems, new AlertTextStyleTransformer),
            trans('validations/api/guest/alert/text/style/index.result.success')
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
         * Getting an alert text style
         */
        $alertTextStyleListItem = AlertTextStyleList::getItem($id);

        /**
         * Checking an alert text style existence
         */
        if (!$alertTextStyleListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/alert/text/style/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($alertTextStyleListItem, new AlertTextStyleTransformer),
            trans('validations/api/guest/alert/text/style/show.result.success')
        );
    }
}
