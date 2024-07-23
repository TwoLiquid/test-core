<?php

namespace App\Http\Controllers\Api\Guest\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Admin\Interfaces\AdminStatusControllerInterface;
use App\Lists\Admin\Status\AdminStatusList;
use App\Transformers\Api\Guest\Admin\Status\AdminStatusTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class AdminStatusController
 *
 * @package App\Http\Controllers\Api\Guest\Admin
 */
final class AdminStatusController extends BaseController implements AdminStatusControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting admin statuses
         */
        $adminStatusListItems = AdminStatusList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($adminStatusListItems, new AdminStatusTransformer),
            trans('validations/api/guest/admin/status/index.result.success')
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
         * Getting admin status
         */
        $adminStatusListItem = AdminStatusList::getItem($id);

        /**
         * Checking admin status existence
         */
        if (!$adminStatusListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/admin/status/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($adminStatusListItem, new AdminStatusTransformer),
            trans('validations/api/guest/admin/status/show.result.success')
        );
    }
}
