<?php

namespace App\Http\Controllers\Api\Guest\Gender;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Gender\Interfaces\GenderControllerInterface;
use App\Lists\Gender\GenderList;
use App\Transformers\Api\Guest\Gender\GenderTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class GenderController
 *
 * @package App\Http\Controllers\Api\Guest\Gender
 */
final class GenderController extends BaseController implements GenderControllerInterface
{
    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        /**
         * Getting genders
         */
        $genderListItems = GenderList::getItems();

        return $this->respondWithSuccess(
            $this->transformCollection($genderListItems, new GenderTransformer),
            trans('validations/api/guest/gender/index.result.success')
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
         * Getting gender
         */
        $genderListItem = GenderList::getItem($id);

        /**
         * Checking gender existence
         */
        if (!$genderListItem) {
            return $this->respondWithError(
                trans('validations/api/guest/gender/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($genderListItem, new GenderTransformer),
            trans('validations/api/guest/gender/show.result.success')
        );
    }
}
