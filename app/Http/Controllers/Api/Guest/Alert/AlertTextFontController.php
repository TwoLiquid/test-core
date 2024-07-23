<?php

namespace App\Http\Controllers\Api\Guest\Alert;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Alert\Interfaces\AlertTextFontControllerInterface;
use App\Lists\Alert\Text\Font\AlertTextFontList;
use App\Transformers\Api\Guest\Alert\AlertTextFontTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class AlertTextFontController
 *
 * @package App\Http\Controllers\Api\Guest\Alert
 */
final class AlertTextFontController extends BaseController implements AlertTextFontControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting an alert text fonts
         */
        $alertTextFontListItems = AlertTextFontList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($alertTextFontListItems, new AlertTextFontTransformer),
            trans('validations/api/guest/alert/text/font/index.result.success')
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
         * Getting an alert text font
         */
        $alertTextFontListItem = AlertTextFontList::getItem($id);

        /**
         * Checking an alert text font existence
         */
        if (!$alertTextFontListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/alert/text/font/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($alertTextFontListItem, new AlertTextFontTransformer),
            trans('validations/api/guest/alert/text/font/show.result.success')
        );
    }
}
