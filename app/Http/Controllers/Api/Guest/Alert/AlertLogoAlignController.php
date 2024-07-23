<?php

namespace App\Http\Controllers\Api\Guest\Alert;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Alert\Interfaces\AlertLogoAlignControllerInterface;
use App\Lists\Alert\Logo\Align\AlertLogoAlignList;
use App\Transformers\Api\Guest\Alert\AlertLogoAlignTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class AlertLogoAlignController
 *
 * @package App\Http\Controllers\Api\Guest\Alert
 */
final class AlertLogoAlignController extends BaseController implements AlertLogoAlignControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting an alert logo aligns
         */
        $alertLogoAlignListItems = AlertLogoAlignList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($alertLogoAlignListItems, new AlertLogoAlignTransformer),
            trans('validations/api/guest/alert/logo/align/index.result.success')
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
         * Getting an alert logo align
         */
        $alertLogoAlignListItem = AlertLogoAlignList::getItem($id);

        /**
         * Checking an alert logo align existence
         */
        if (!$alertLogoAlignListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/alert/logo/align/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($alertLogoAlignListItem, new AlertLogoAlignTransformer),
            trans('validations/api/guest/alert/logo/align/show.result.success')
        );
    }
}
