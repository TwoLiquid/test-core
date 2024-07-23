<?php

namespace App\Http\Controllers\Api\Guest\Alert;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Alert\Interfaces\AlertAnimationControllerInterface;
use App\Lists\Alert\Animation\AlertAnimationList;
use App\Transformers\Api\Guest\Alert\AlertAnimationTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class AlertAnimationController
 *
 * @package App\Http\Controllers\Api\Guest\Alert
 */
final class AlertAnimationController extends BaseController implements AlertAnimationControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting an alert animations
         */
        $alertAnimationListItems = AlertAnimationList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($alertAnimationListItems, new AlertAnimationTransformer),
            trans('validations/api/guest/alert/animation/index.result.success')
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
         * Getting an alert animation
         */
        $alertAnimationListItem = AlertAnimationList::getItem($id);

        /**
         * Checking an alert animation existence
         */
        if (!$alertAnimationListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/alert/animation/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($alertAnimationListItem, new AlertAnimationTransformer),
            trans('validations/api/guest/alert/animation/show.result.success')
        );
    }
}
