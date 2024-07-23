<?php

namespace App\Http\Controllers\Api\General\Dashboard\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Vybe\Interfaces\VybeControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Vybe\GetMoreVybesRequest;
use App\Http\Requests\Api\General\Dashboard\Vybe\IndexRequest;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Activity\ActivityService;
use App\Services\Auth\AuthService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Dashboard\Vybe\Form\VybeFormTransformer;
use App\Transformers\Api\General\Dashboard\Vybe\VybePageTransformer;
use App\Transformers\Api\General\Dashboard\Vybe\VybeTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Exception;

/**
 * Class VybeController
 *
 * @package App\Http\Controllers\Api\General\Dashboard
 */
final class VybeController extends BaseController implements VybeControllerInterface
{
    /**
     * @var ActivityImageRepository
     */
    protected ActivityImageRepository $activityImageRepository;

    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * VybeController constructor
     */
    public function __construct()
    {
        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
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
         * Getting uncompleted vybes with pagination
         */
        $uncompletedVybes = $this->vybeRepository->getUncompletedForDashboard(
            AuthService::user()
        );

        /**
         * Getting paginated uncompleted vybes
         */
        $uncompletedVybesCount = $uncompletedVybes->count();

        /**
         * Getting paginated uncompleted vybes
         */
        $paginatedUncompletedVybes = paginateCollection(
            $uncompletedVybes,
            $request->input('per_page')
        );

        /**
         * Preparing solo vybes variable
         */
        $paginatedSoloVybes = null;
        $soloVybesCount = null;

        /**
         * Checking solo vybes existence
         */
        if (in_array(
            VybeTypeList::getSoloItem()->id,
            $request->input('types_ids'))
        ) {

            /**
             * Getting event vybes with pagination
             */
            $soloVybes = $this->vybeRepository->getWithFiltersForDashboard(
                AuthService::user(),
                [VybeTypeList::getSoloItem()->id],
                $request->input('statuses_ids')
            );

            /**
             * Getting solo vybes counts
             */
            $soloVybesCount = $soloVybes->count();

            /**
             * Getting paginated solo vybes
             */
            $paginatedSoloVybes = paginateCollection(
                $soloVybes,
                $request->input('per_page')
            );
        }

        /**
         * Preparing group vybes variable
         */
        $paginatedGroupVybes = null;
        $groupVybesCount = null;

        /**
         * Checking group vybes existence
         */
        if (in_array(
            VybeTypeList::getGroupItem()->id,
            $request->input('types_ids'))
        ) {

            /**
             * Getting group vybes with pagination
             */
            $groupVybes = $this->vybeRepository->getWithFiltersForDashboard(
                AuthService::user(),
                [VybeTypeList::getGroupItem()->id],
                $request->input('statuses_ids')
            );

            /**
             * Getting paginated solo vybes
             */
            $groupVybesCount = $groupVybes->count();

            /**
             * Getting paginated group vybes
             */
            $paginatedGroupVybes = paginateCollection(
                $groupVybes,
                $request->input('per_page')
            );
        }

        /**
         * Preparing event vybes variable
         */
        $paginatedEventVybes = null;
        $eventVybesCount = null;

        /**
         * Checking event vybes existence
         */
        if (in_array(
            VybeTypeList::getEventItem()->id,
            $request->input('types_ids'))
        ) {

            /**
             * Getting event vybes with pagination
             */
            $eventVybes = $this->vybeRepository->getWithFiltersForDashboard(
                AuthService::user(),
                [VybeTypeList::getEventItem()->id],
                $request->input('statuses_ids')
            );

            /**
             * Getting paginated event vybes
             */
            $eventVybesCount = $eventVybes->count();

            /**
             * Getting paginated event vybes
             */
            $paginatedEventVybes = paginateCollection(
                $eventVybes,
                $request->input('per_page')
            );
        }

        /**
         * Getting vybe form
         */
        $vybeForm = $this->transformItem([],
            new VybeFormTransformer
        );

        $paginatedUncompletedVybes = new Collection(
            $paginatedUncompletedVybes->items()
        );

        $paginatedSoloVybes = $paginatedSoloVybes ?
            new Collection($paginatedSoloVybes->items()) :
            null;

        $paginatedGroupVybes = $paginatedGroupVybes ?
            new Collection($paginatedGroupVybes->items()) :
            null;

        $paginatedEventVybes = $paginatedEventVybes ?
            new Collection($paginatedEventVybes->items()) :
            null;

        /**
         * Getting merged vybes
         */
        $mergedVybes = mergeCollections([
            $paginatedSoloVybes,
            $paginatedSoloVybes,
            $paginatedEventVybes
        ]);

        return $this->respondWithSuccess(
            $this->transformItem([],
                new VybePageTransformer(
                    $paginatedUncompletedVybes,
                    $paginatedSoloVybes,
                    $paginatedGroupVybes,
                    $paginatedEventVybes,
                    $this->vybeImageRepository->getByVybes(
                        $mergedVybes
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        $mergedVybes
                    ),
                    $this->activityImageRepository->getByActivities(
                        $this->activityService->getByVybes(
                            $mergedVybes
                        )
                    ),
                    $uncompletedVybesCount,
                    $soloVybesCount,
                    $groupVybesCount,
                    $eventVybesCount,
                    $request->input('per_page'),
                    $request->input('page')
                )
            )['vybe_page'] + $vybeForm,
            trans('validations/api/general/dashboard/vybe/index.result.success')
        );
    }

    /**
     * @param GetMoreVybesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getMoreVybes(
        GetMoreVybesRequest $request
    ) : JsonResponse
    {
        if ($request->input('uncompleted')) {

            /**
             * Getting vybes with pagination
             */
            $vybes = $this->vybeRepository->getUncompletedForDashboard(
                AuthService::user()
            );
        } else {

            /**
             * Getting vybes with pagination
             */
            $vybes = $this->vybeRepository->getWithFiltersForDashboard(
                AuthService::user(),
                $request->input('types_ids'),
                $request->input('statuses_ids'),
            );
        }

        /**
         * Getting vybes count
         */
        $vybesCount = $vybes->count();

        /**
         * Getting paginated vybes
         */
        $paginatedVybes = paginateCollection(
            $vybes,
            $request->input('per_page')
        );

        return $this->setPagination($paginatedVybes)->respondWithSuccess(
            $this->transformCollection(
                $paginatedVybes,
                new VybeTransformer(
                    $this->vybeImageRepository->getByVybes(
                        new Collection($paginatedVybes->items())
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection($paginatedVybes->items())
                    ),
                    $this->activityImageRepository->getByActivities(
                        new Collection($paginatedVybes->items())
                    )
                )
            ) + [
                'count' => $vybesCount
            ], trans('validations/api/general/dashboard/vybe/getMoreVybes.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function deleteUncompleted(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/profile/vybe/deleteUncompleted.result.error.find')
            );
        }

        /**
         * Checking vybe to user belonging
         */
        if (!$this->vybeRepository->belongsToUser(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/profile/vybe/deleteUncompleted.result.error.belongsToUser')
            );
        }

        /**
         * Checking vybe step
         */
        if ($vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/profile/vybe/deleteUncompleted.result.error.step')
            );
        }

        /**
         * Checking images ids existence
         */
        if ($vybe->images_ids) {

            try {

                /**
                 * Deleting all vybe images
                 */
                $this->mediaMicroservice->deleteVybeImages(
                    $vybe->images_ids
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Checking videos ids existence
         */
        if ($vybe->videos_ids) {

            try {

                /**
                 * Deleting all vybe videos
                 */
                $this->mediaMicroservice->deleteVybeVideos(
                    $vybe->videos_ids
                );
            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }
        }

        /**
         * Delete vybe
         */
        $this->vybeService->delete(
            $vybe
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/profile/vybe/deleteUncompleted.result.success')
        );
    }
}
