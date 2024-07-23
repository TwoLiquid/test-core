<?php

namespace App\Http\Controllers\Api\Guest\Setting\Type;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Setting\Type\Interfaces\SettingTypeControllerInterface;
use App\Lists\Setting\Type\SettingTypeList;
use App\Transformers\Api\Guest\Setting\Type\SettingTypeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class SettingTypeController
 *
 * @package App\Http\Controllers\Api\Guest\Setting\Type
 */
final class SettingTypeController extends BaseController implements SettingTypeControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting a setting types
         */
        $settingTypeListItems = SettingTypeList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($settingTypeListItems, new SettingTypeTransformer),
            trans('validations/api/guest/setting/type/index.result.success')
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
         * Getting a setting type
         */
        $settingTypeListItem = SettingTypeList::getItem($id);

        /**
         * Checking setting type existence
         */
        if (!$settingTypeListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/setting/type/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($settingTypeListItem, new SettingTypeTransformer),
            trans('validations/api/guest/setting/type/show.result.success')
        );
    }
}
