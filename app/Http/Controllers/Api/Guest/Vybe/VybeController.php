<?php

namespace App\Http\Controllers\Api\Guest\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Vybe\Interfaces\VybeControllerInterface;
use App\Http\Requests\Api\Guest\Vybe\GetCalendarRequest;
use App\Repositories\Media\PlatformIconRepository;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\Platform\PlatformService;
use App\Services\User\UserService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Guest\Vybe\Calendar\CalendarPageTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeController
 *
 * @package App\Http\Controllers\Api\Guest\Vybe
 */
final class VybeController extends BaseController implements VybeControllerInterface
{
    /**
     * @var PlatformIconRepository
     */
    protected PlatformIconRepository $platformIconRepository;

    /**
     * @var PlatformService
     */
    protected PlatformService $platformService;

    /**
     * @var UserAvatarRepository
     */
    protected UserAvatarRepository $userAvatarRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * VybeController constructor
     */
    public function __construct()
    {
        /** @var PlatformIconRepository platformIconRepository */
        $this->platformIconRepository = new PlatformIconRepository();

        /** @var PlatformService platformService */
        $this->platformService = new PlatformService();

        /** @var UserAvatarRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeService vybeService */
        $this->vybeService = new VybeService();
    }

    /**
     * @param int $id
     * @param GetCalendarRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getCalendar(
        int $id,
        GetCalendarRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findFullById(
            $id
        );

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondNotFound(
                trans('validations/api/guest/vybe/getCalendar.result.error.find')
            );
        }

        /**
         * Checking vybe status
         */
        if ($vybe->getStatus()->isDraft()) {
            return $this->respondNotFound(
                trans('validations/api/guest/vybe/getCalendar.result.error.status')
            );
        }

        /**
         * Checking vybe type
         */
        if ($vybe->getType()->isSolo()) {

            /**
             * Getting vybe solo calendar for order
             */
            $calendar = $this->vybeService->getSoloCalendarForOrder(
                $vybe,
                Carbon::now(),
                AuthService::user() ?
                    AuthService::user()
                        ->timezone
                        ->getCurrentOffset()
                        ->offset :
                    $request->input('offset')
            );
        } elseif ($vybe->getType()->isGroup()) {

            /**
             * Getting vybe group calendar for order
             */
            $calendar = $this->vybeService->getGroupCalendarForOrder(
                $vybe,
                Carbon::now(),
                AuthService::user(),
                AuthService::user() ?
                    AuthService::user()
                        ->timezone
                        ->getCurrentOffset()
                        ->offset :
                    $request->input('offset')
            );
        } else {

            /**
             * Getting vybe event calendar for order
             */
            $calendar = $this->vybeService->getEventCalendarForOrder(
                $vybe,
                AuthService::user(),
                AuthService::user() ?
                    AuthService::user()
                        ->timezone
                        ->getCurrentOffset()
                        ->offset :
                    $request->input('offset')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem([],
                new CalendarPageTransformer(
                    $vybe,
                    $calendar,
                    $this->userAvatarRepository->getByUsers(
                        $this->userService->getUsersFromSlots(
                            $calendar
                        )->push(
                            $vybe->user
                        )
                    ),
                    $this->platformIconRepository->getByPlatforms(
                        $this->platformService->getByAppearanceCases(
                            $vybe->appearanceCases
                        )
                    )
                )
            )['vybe_calendar_page'],
            trans('validations/api/guest/vybe/getCalendar.result.success')
        );
    }
}
