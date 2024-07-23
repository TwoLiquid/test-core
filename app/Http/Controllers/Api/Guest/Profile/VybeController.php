<?php

namespace App\Http\Controllers\Api\Guest\Profile;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Guest\Profile\Interfaces\VybeControllerInterface;
use App\Http\Requests\Api\Guest\Profile\Vybe\GetTimeslotUsersRequest;
use App\Http\Requests\Api\Guest\Profile\Vybe\ShowRequest;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Timeslot\TimeslotRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\Timeslot\TimeslotService;
use App\Services\User\UserService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\Guest\Profile\Vybe\Vybe\Schedule\Timeslot\UserTransformer;
use App\Transformers\Api\Guest\Profile\Vybe\Vybe\VybeShortTransformer;
use App\Transformers\Api\Guest\Profile\Vybe\Vybe\VybeTransformer;
use App\Transformers\Api\Guest\Profile\Vybe\VybeAgeLimitTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeController
 *
 * @package App\Http\Controllers\Api\Guest\Profile
 */
final class VybeController extends BaseController implements VybeControllerInterface
{
    /**
     * @var TimeslotRepository
     */
    protected TimeslotRepository $timeslotRepository;

    /**
     * @var TimeslotService
     */
    protected TimeslotService $timeslotService;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var UserAvatarRepository
     */
    protected UserAvatarRepository $userAvatarRepository;

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
        /** @var TimeslotRepository timeslotRepository */
        $this->timeslotRepository = new TimeslotRepository();

        /** @var TimeslotService timeslotService */
        $this->timeslotService = new TimeslotService();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var UserAvatarRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();

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
     * @param string $username
     * @param int $id
     * @param ShowRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        string $username,
        int $id,
        ShowRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByUsername(
            $username
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondNotFound(
                trans('validations/api/guest/profile/vybe/show.result.error.find.user')
            );
        }

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
                trans('validations/api/guest/profile/vybe/show.result.error.find.vybe')
            );
        }

        /**
         * Checking vybe to user belonging
         */
        if (!$this->vybeRepository->belongsToUser(
            $vybe,
            $user
        )) {
            return $this->respondWithError(
                trans('validations/api/guest/profile/vybe/show.result.error.belongsToUser')
            );
        }

        /**
         * Checking vybe status
         */
        if (!$vybe->getStatus()->isPublished() &&
            !$vybe->getStatus()->isPaused()
        ) {
            return $this->respondNotFound(
                trans('validations/api/guest/profile/vybe/show.result.error.published')
            );
        }

        /**
         * Checking authorized user existence
         */
        if (AuthService::user()) {

            /**
             * Checking vybe age limit for user
             */
            if (!$this->vybeService->checkUserAgeLimit(
                $vybe,
                AuthService::user())
            ) {
                return $this->respondWithErrors(
                    $this->transformItem(
                        $vybe->getAgeLimit(),
                        new VybeAgeLimitTransformer
                    ), trans('validations/api/guest/profile/vybe/show.result.error.vybeAgeLimit'),
                    403
                );
            }
        }

        /**
         * Checking vybe access
         */
        if ($vybe->getAccess()->isPrivate()) {

            return $this->respondWithSuccess(
                $this->transformItem(
                    $vybe,
                    new VybeShortTransformer(
                        $this->vybeImageRepository->getByVybes(
                            new Collection([$vybe])
                        ),
                        $this->vybeVideoRepository->getByVybes(
                            new Collection([$vybe])
                        )
                    )
                ), trans('validations/api/guest/profile/vybe/show.result.success')
            );
        }

        /**
         * Getting timeslots
         */
        $timeslots = $this->timeslotService->getFutureForVybe(
            $vybe
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $vybe,
                new VybeTransformer(
                    AuthService::user() ?
                        AuthService::user()
                            ->timezone
                            ->getCurrentOffset()
                            ->offset :
                        $request->input('offset'),
                    $timeslots,
                    $this->vybeImageRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection([$vybe])
                    ),
                    $this->userAvatarRepository->getByUsers(
                        $this->userService->getByTimeslots(
                            $timeslots
                        )
                    )
                )
            ), trans('validations/api/guest/profile/vybe/show.result.success')
        );
    }

    /**
     * @param int $id
     * @param GetTimeslotUsersRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getTimeslotUsers(
        int $id,
        GetTimeslotUsersRequest $request
    ) : JsonResponse
    {
        /**
         * Getting timeslot
         */
        $timeslot = $this->timeslotRepository->findById($id);

        /**
         * Checking timeslot existence
         */
        if (!$timeslot) {
            return $this->respondWithError(
                trans('validations/api/guest/profile/vybe/getTimeslotUsers.result.error.find')
            );
        }

        /**
         * Checking paginated flag
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting paginated users
             */
            $users = $this->userRepository->getForTimeslotPaginated(
                $timeslot,
                $request->input('username'),
                $request->input('page'),
                $request->input('per_page')
            );

            return $this->setPagination($users)->respondWithSuccess(
                $this->transformCollection(
                    $users,
                    new UserTransformer(
                        $this->userAvatarRepository->getByUsers(
                            new Collection($users->items())
                        )
                    )
                ), trans('validations/api/guest/profile/vybe/getTimeslotUsers.result.success')
            );
        }

        /**
         * Getting users
         */
        $users = $this->userRepository->getForTimeslot(
            $timeslot,
            $request->input('username')
        );

        return $this->respondWithSuccess(
            $this->transformCollection(
                $users, new UserTransformer(
                    $this->userAvatarRepository->getByUsers(
                        $users
                    )
                )
            ), trans('validations/api/guest/profile/vybe/getTimeslotUsers.result.success')
        );
    }
}
