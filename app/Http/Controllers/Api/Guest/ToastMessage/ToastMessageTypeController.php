<?php

namespace App\Http\Controllers\Api\Guest\ToastMessage;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\ToastMessage\Interfaces\ToastMessageTypeControllerInterface;
use App\Lists\ToastMessage\Type\ToastMessageTypeList;
use App\Transformers\Api\Guest\ToastMessage\Type\ToastMessageTypeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class ToastMessageTypeController
 *
 * @package App\Http\Controllers\Api\Guest\ToastMessage
 */
final class ToastMessageTypeController extends BaseController implements ToastMessageTypeControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting a toast message types
         */
        $toastMessageTypeListItems = ToastMessageTypeList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($toastMessageTypeListItems, new ToastMessageTypeTransformer),
            trans('validations/api/guest/toastMessage/type/index.result.success')
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
         * Getting a toast message type
         */
        $toastMessageTypeListItem = ToastMessageTypeList::getItem($id);

        /**
         * Checking toast message type existence
         */
        if (!$toastMessageTypeListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/toastMessage/type/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($toastMessageTypeListItem, new ToastMessageTypeTransformer),
            trans('validations/api/guest/toastMessage/type/show.result.success')
        );
    }
}
