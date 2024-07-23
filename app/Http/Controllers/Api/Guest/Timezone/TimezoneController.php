<?php

namespace App\Http\Controllers\Api\Guest\Timezone;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Timezone\Interfaces\TimezoneControllerInterface;
use App\Repositories\Timezone\TimezoneRepository;
use App\Transformers\Api\Guest\Timezone\TimezoneTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class TimezoneController
 *
 * @package App\Http\Controllers\Api\Guest\Timezone
 */
final class TimezoneController extends BaseController implements TimezoneControllerInterface
{
    /**
     * @var TimezoneRepository
     */
    protected TimezoneRepository $timezoneRepository;

    /**
     * TimezoneController constructor
     */
    public function __construct()
    {
        /** @var TimezoneRepository timezoneRepository */
        $this->timezoneRepository = new TimezoneRepository();
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index() : JsonResponse
    {
        /**
         * Getting timezones
         */
        $timezones = $this->timezoneRepository->getAll();

        return $this->respondWithSuccess(
            $this->transformCollection($timezones, new TimezoneTransformer),
            trans('validations/api/guest/timezone/index.result.success')
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
         * Getting timezone
         */
        $timezone = $this->timezoneRepository->findById($id);

        /**
         * Checking timezone existence
         */
        if (!$timezone) {
            return $this->respondWithError(
                trans('validations/api/guest/timezone/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($timezone, new TimezoneTransformer),
            trans('validations/api/guest/timezone/show.result.success')
        );
    }
}
