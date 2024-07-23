<?php

namespace App\Http\Controllers\Api\General\Vybe;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Vybe\Interfaces\VybeControllerInterface;
use App\Http\Requests\Api\General\Vybe\ShowRequest;
use App\Http\Requests\Api\General\Vybe\UpdateRequest;
use App\Lists\Vybe\Access\VybeAccessList;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Lists\Vybe\Showcase\VybeShowcaseList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Microservices\Media\MediaMicroservice;
use App\Microservices\Media\Responses\VybeImageCollectionResponse;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Media\PlatformIconRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Platform\PlatformRepository;
use App\Repositories\Schedule\ScheduleRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\Platform\PlatformService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Vybe\VybePageTransformer;
use Dedicated\GoogleTranslate\TranslateException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Spatie\GoogleTimeZone\Exceptions\GoogleTimeZoneException;
use Exception;

/**
 * Class VybeController
 *
 * @package App\Http\Controllers\Api\General\Vybe
 */
final class VybeController extends BaseController implements VybeControllerInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var PlatformIconRepository
     */
    protected PlatformIconRepository $platformIconRepository;

    /**
     * @var PlatformRepository
     */
    protected PlatformRepository $platformRepository;

    /**
     * @var PlatformService
     */
    protected PlatformService $platformService;

    /**
     * @var ScheduleRepository
     */
    protected ScheduleRepository $scheduleRepository;

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
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();

        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var PlatformIconRepository platformIconRepository */
        $this->platformIconRepository = new PlatformIconRepository();

        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();

        /** @var PlatformService platformService */
        $this->platformService = new PlatformService();

        /** @var ScheduleRepository scheduleRepository */
        $this->scheduleRepository = new ScheduleRepository();

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
     * @param int $id
     * @param ShowRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id,
        ShowRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe || $vybe->getStatus()->isDeleted()) {
            return $this->respondNotFound(
                trans('validations/api/general/vybe/show.result.error.find')
            );
        }

        /**
         * Returning without media
         */
        return $this->respondWithSuccess(
            $this->transformItem(
                $vybe,
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByVybe(
                            $vybe
                        )
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/show.result.success')
        );
    }

    /**
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getFormData() : JsonResponse
    {
        return $this->respondWithSuccess(
            $this->transformItem([],
                new VybePageTransformer(
                    null,
                    null,
                    null,
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformRepository->getAll()
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/getFormData.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws GoogleTimeZoneException
     * @throws GuzzleException
     * @throws TranslateException
     * @throws ValidationException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/update.result.error.find')
            );
        }

        /**
         * Checkin is a vybe owner
         */
        if (!$this->vybeService->isOwner(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/update.result.error.owner')
            );
        }

        /**
         * Checking vybe is completed
         */
        if (!$vybe->getStep()->isCompleted()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/update.result.error.completed')
            );
        }

        /**
         * Checking vybe status
         */
        if (!$vybe->getStatus()->isDraft()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/update.result.error.status')
            );
        }

        /**
         * Checking any vybe pending request existence
         */
        if ($this->vybeService->checkIfAnyRequestExists(
            $vybe
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/update.result.error.request')
            );
        }

        /**
         * Updating 1-st step
         */

        /**
         * Getting category
         */
        $category = $this->categoryRepository->findById(
            $request->input('category_id')
        );

        /**
         * Getting subcategory
         */
        $subcategory = $this->categoryRepository->findById(
            $request->input('subcategory_id')
        );

        /**
         * Getting activity
         */
        $activity = $this->activityRepository->findById(
            $request->input('activity_id')
        );

        /**
         * Getting vybe period
         */
        $vybePeriodListItem = VybePeriodList::getItem(
            $request->input('period_id')
        );

        /**
         * Preparing devices variable
         */
        $devices = null;

        /**
         * Checking device ids existence
         */
        if ($request->input('devices_ids')) {

            /**
             * Getting devices
             */
            $devices = $this->deviceRepository->getByIds(
                $request->input('devices_ids')
            );
        }

        /**
         * Getting vybe
         */
        $vybeType = $this->vybeService->getVybeType(
            $vybePeriodListItem,
            $request->input('user_count')
        );

        /**
         * Checking vybe type user count
         */
        if ($vybeType &&
            $request->input('user_count')
        ) {
            if ($this->vybeService->isUserCountNotAllowed(
                $vybeType,
                $request->input('user_count')
            )) {
                return $this->respondWithErrors([
                    'user_count' => [
                        trans('validations/api/general/vybe/update.result.error.userCount.max')
                    ]
                ]);
            }
        }

        /**
         * Updating vybe
         */
        $vybe = $this->vybeService->updateFirstStep(
            $vybe,
            $request->input('title'),
            $category,
            $request->input('category_suggestion'),
            $subcategory,
            $request->input('subcategory_suggestion'),
            $devices,
            $request->input('device_suggestion'),
            $activity,
            $request->input('activity_suggestion'),
            $vybePeriodListItem,
            $request->input('user_count')
        );

        /**
         * Updating vybe type
         */
        $vybe = $this->vybeService->updateVybeType(
            $vybe
        );

        /**
         * Updating 2-nd step
         */

        /**
         * Deleting existing vybe appearance cases
         */
        $this->vybeService->deleteAppearanceCasesForVybe(
            $vybe
        );

        /**
         * Creating vybe appearance cases
         */
        $this->vybeService->createAppearanceCases(
            $vybe,
            $request->input('appearance_cases')
        );

        /**
         * Updating 3-rd step
         */

        /**
         * Deleting existing vybe schedules
         */
        $this->scheduleRepository->deleteForceForVybe(
            $vybe
        );

        /**
         * Updating draft vybe schedules
         */
        $this->vybeService->createSchedules(
            $vybe,
            $request->input('schedules')
        );

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->updateOrderAdvance(
            $vybe,
            $request->input('order_advance')
        );

        /**
         * Updating 4-th step
         */

        /**
         * Checking files upload availability
         */
        $this->vybeService->checkFilesUploadAvailability(
            $request->input('files'),
            $vybe->images_ids,
            $vybe->videos_ids,
            $request->input('deleted_images_ids'),
            $request->input('deleted_videos_ids')
        );

        /**
         * Checking files existence
         */
        if ($request->input('files')) {

            /**
             * Validating files
             */
            $this->vybeService->validateFiles(
                $request->input('files')
            );

            try {

                /**
                 * Getting file images
                 */
                $fileImages = $this->vybeService->getImagesFromFiles(
                    $request->input('files')
                );

                /**
                 * Checking file images existence
                 */
                if (!empty($fileImages)) {

                    /** @var VybeImageCollectionResponse $vybeImages */
                    $vybeImageCollection = $this->mediaMicroservice->storeVybeImages(
                        $fileImages
                    );

                    /**
                     * Updating vybe
                     */
                    $this->vybeRepository->updateMediaIds(
                        $vybe,
                        array_merge(
                            $vybe->images_ids ?
                                $vybe->images_ids :
                                [],
                            $vybeImageCollection->images
                                ->pluck('id')
                                ->toArray()
                        ),
                        null
                    );
                }

            } catch (Exception $exception) {

                /**
                 * Adding background error to controller stack
                 */
                $this->addBackgroundError(
                    $exception
                );
            }

            try {

                /**
                 * Getting file videos
                 */
                $fileVideos = $this->vybeService->getVideosFromFiles(
                    $request->input('files')
                );

                /**
                 * Checking file videos existence
                 */
                if (!empty($fileVideos)) {

                    /**
                     * Getting vybe vide response collection
                     */
                    $vybeVideoCollection = $this->mediaMicroservice->storeVybeVideos(
                        $fileVideos
                    );

                    /**
                     * Updating vybe
                     */
                    $this->vybeRepository->updateMediaIds(
                        $vybe,
                        null,
                        array_merge(
                            $vybe->videos_ids ?
                                $vybe->videos_ids :
                                [],
                            $vybeVideoCollection->videos
                                ->pluck('id')
                                ->toArray()
                        )
                    );
                }

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
         * Updating 5-th step
         */

        /**
         * Getting vybe access
         */
        $vybeAccessListItem = VybeAccessList::getItem(
            $request->input('access_id')
        );

        /**
         * Getting vybe showcase
         */
        $vybeShowcaseListItem = VybeShowcaseList::getItem(
            $request->input('showcase_id')
        );

        /**
         * Getting vybe showcase
         */
        $vybeOrderAcceptListItem = VybeOrderAcceptList::getItem(
            $request->input('order_accept_id')
        );

        /**
         * Getting vybe status
         */
        $vybeStatusListItem = VybeStatusList::getItem(
            $request->input('status_id')
        );

        /**
         * Updating vybe
         */
        $vybe = $this->vybeRepository->updateFifthStep(
            $vybe,
            $vybeAccessListItem,
            $vybeShowcaseListItem,
            $vybeStatusListItem,
            $vybeOrderAcceptListItem
        );

        /**
         * Updating vybe
         */
        $this->vybeRepository->updateAccessPassword(
            $vybe,
            $request->input('access_password')
        );

        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        return $this->respondWithSuccess(
            $this->transformItem(
                $vybe,
                new VybePageTransformer(
                    $vybe,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    AuthService::user()
                )
            )['vybe_page'],
            trans('validations/api/general/vybe/update.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function rejectSuspended(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/rejectSuspended.result.error.find')
            );
        }

        /**
         * Checking is an owner
         */
        if (!$this->vybeService->isOwner(
            $vybe,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/rejectSuspended.result.error.owner')
            );
        }

        /**
         * Checking vybe status
         */
        if (!$vybe->getStatus()->isSuspended()) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/rejectSuspended.result.error.status')
            );
        }

        return $this->respondWithError(
            trans('validations/api/general/vybe/rejectSuspended.result.error.suspended')
        );
    }
}
