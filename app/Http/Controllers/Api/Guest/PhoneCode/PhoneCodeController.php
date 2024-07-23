<?php

namespace App\Http\Controllers\Api\Guest\PhoneCode;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\PhoneCode\Interfaces\PhoneCodeControllerInterface;
use App\Http\Requests\Api\Guest\PhoneCode\IndexRequest;
use App\Repositories\PhoneCode\PhoneCodeRepository;
use App\Repositories\Place\CountryPlaceRepository;
use App\Transformers\Api\Guest\PhoneCode\PhoneCodeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class PhoneCodeController
 *
 * @package App\Http\Controllers\Api\Guest\PhoneCode
 */
final class PhoneCodeController extends BaseController implements PhoneCodeControllerInterface
{
    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * @var PhoneCodeRepository
     */
    protected PhoneCodeRepository $phoneCodeRepository;

    /**
     * PhoneCodeController constructor
     */
    public function __construct()
    {
        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var PhoneCodeRepository phoneCodeRepository */
        $this->phoneCodeRepository = new PhoneCodeRepository();
    }

    /**
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Checking paginated enabled
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting phone codes with pagination
             */
            $phoneCodes = $this->phoneCodeRepository->getAllPaginated();

            /**
             * Returning without media
             */
            return $this->setPagination($phoneCodes)->respondWithSuccess(
                $this->transformCollection($phoneCodes, new PhoneCodeTransformer),
                trans('validations/api/guest/phoneCode/index.result.success')
            );
        }

        /**
         * Getting phone codes
         */
        $phoneCodes = $this->phoneCodeRepository->getAll();

        return $this->respondWithSuccess(
            $this->transformCollection($phoneCodes, new PhoneCodeTransformer),
            trans('validations/api/guest/phoneCode/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting phone code
         */
        $phoneCode = $this->phoneCodeRepository->findById(
            $id
        );

        /**
         * Checking phone code existence
         */
        if (!$phoneCode) {
            return $this->respondWithError(
                trans('validations/api/guest/phoneCode/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($phoneCode, new PhoneCodeTransformer),
            trans('validations/api/guest/phoneCode/show.result.success')
        );
    }
}
