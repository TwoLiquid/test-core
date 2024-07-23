<?php

namespace App\Http\Controllers\Api\General\Dashboard\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Vybe\Interfaces\FavoriteVybeControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Vybe\Favorite\GetMoreFavoriteVybesRequest;
use App\Http\Requests\Api\General\Dashboard\Vybe\Favorite\IndexRequest;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Repositories\Media\UserAvatarRepository;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Dashboard\Vybe\FavoriteVybePageTransformer;
use App\Transformers\Api\General\Dashboard\Vybe\FavoriteVybeTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class FavoriteVybeController
 *
 * @package App\Http\Controllers\Api\General\Dashboard
 */
final class FavoriteVybeController extends BaseController implements FavoriteVybeControllerInterface
{
    /**
     * @var UserAvatarRepository
     */
    protected UserAvatarRepository $userAvatarRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * @var VybeService
     */
    protected VybeService $vybeService;

    /**
     * FavoriteVybeController constructor
     */
    public function __construct()
    {
        /** @var UserAvatarRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

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
         * Preparing solo favorite vybes variable
         */
        $paginatedSoloFavoriteVybes = null;
        $soloFavoriteVybesCount = null;

        /**
         * Checking solo favorite vybes existence
         */
        if (in_array(
            VybeTypeList::getSoloItem()->id,
            $request->input('types_ids')
        )) {

            /**
             * Getting event favorite vybes with pagination
             */
            $soloFavoriteVybes = $this->vybeRepository->getFavoritesWithFiltersForDashboard(
                AuthService::user(),
                [VybeTypeList::getSoloItem()->id]
            );

            /**
             * Getting solo favorite vybes count
             */
            $soloFavoriteVybesCount = $soloFavoriteVybes->count();

            /**
             * Getting paginated solo favorite vybes
             */
            $paginatedSoloFavoriteVybes = paginateCollection(
                $soloFavoriteVybes,
                $request->input('per_page')
            );
        }

        /**
         * Preparing group favorite vybes variable
         */
        $paginatedGroupFavoriteVybes = null;
        $groupFavoriteVybesCount = null;

        /**
         * Checking group favorite vybes existence
         */
        if (in_array(
            VybeTypeList::getGroupItem()->id,
            $request->input('types_ids')
        )) {

            /**
             * Getting group favorite vybes with pagination
             */
            $groupFavoriteVybes = $this->vybeRepository->getFavoritesWithFiltersForDashboard(
                AuthService::user(),
                [VybeTypeList::getGroupItem()->id]
            );

            /**
             * Getting paginated solo favorite vybes
             */
            $groupFavoriteVybesCount = $groupFavoriteVybes->count();

            /**
             * Getting paginated group vybes
             */
            $paginatedGroupFavoriteVybes = paginateCollection(
                $groupFavoriteVybes,
                $request->input('per_page')
            );
        }

        /**
         * Preparing event favorite vybes variable
         */
        $paginatedEventFavoriteVybes = null;
        $eventFavoriteVybesCount = null;

        /**
         * Checking event favorite vybes existence
         */
        if (in_array(
            VybeTypeList::getEventItem()->id,
            $request->input('types_ids')
        )) {

            /**
             * Getting event favorite vybes with pagination
             */
            $eventFavoriteVybes = $this->vybeRepository->getFavoritesWithFiltersForDashboard(
                AuthService::user(),
                [VybeTypeList::getEventItem()->id]
            );

            /**
             * Getting paginated event favorite vybes
             */
            $eventFavoriteVybesCount = $eventFavoriteVybes->count();

            /**
             * Getting paginated event favorite vybes
             */
            $paginatedEventFavoriteVybes = paginateCollection(
                $eventFavoriteVybes,
                $request->input('per_page')
            );
        }

        $paginatedSoloFavoriteVybes = $paginatedSoloFavoriteVybes ?
            new Collection($paginatedSoloFavoriteVybes->items()) :
            null;

        $paginatedGroupFavoriteVybes = $paginatedGroupFavoriteVybes ?
            new Collection($paginatedGroupFavoriteVybes->items()) :
            null;

        $paginatedEventFavoriteVybes = $paginatedEventFavoriteVybes ?
            new Collection($paginatedEventFavoriteVybes->items()) :
            null;

        return $this->respondWithSuccess(
            $this->transformItem([],
                new FavoriteVybePageTransformer(
                    $paginatedSoloFavoriteVybes,
                    $paginatedGroupFavoriteVybes,
                    $paginatedEventFavoriteVybes,
                    $this->vybeImageRepository->getByVybes(
                        $paginatedSoloFavoriteVybes->merge(
                            $paginatedGroupFavoriteVybes->merge(
                                $paginatedEventFavoriteVybes
                            )
                        )
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        $paginatedSoloFavoriteVybes->merge(
                            $paginatedGroupFavoriteVybes->merge(
                                $paginatedEventFavoriteVybes
                            )
                        )
                    ),
                    $this->userAvatarRepository->getByUsers(
                        $this->userService->getByVybes(
                            $paginatedSoloFavoriteVybes->merge(
                                $paginatedGroupFavoriteVybes->merge(
                                    $paginatedEventFavoriteVybes
                                )
                            )
                        )
                    ),
                    $soloFavoriteVybesCount,
                    $groupFavoriteVybesCount,
                    $eventFavoriteVybesCount,
                    $request->input('per_page'),
                    $request->input('page')
                )
            )['vybe_page'],
            trans('validations/api/general/dashboard/vybe/favorite/index.result.success')
        );
    }

    /**
     * @param GetMoreFavoriteVybesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getMoreFavoriteVybes(
        GetMoreFavoriteVybesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting a favorite vybe type
         */
        $vybeTypeListItem = VybeTypeList::getItem(
            $request->input('type_id')
        );

        /**
         * Getting favorite vybes with pagination
         */
        $favoriteVybes = $this->vybeRepository->getFavoritesWithFiltersForDashboard(
            AuthService::user(),
            [$vybeTypeListItem->id]
        );

        /**
         * Getting favorite vybes count
         */
        $favoriteVybesCount = $favoriteVybes->count();

        /**
         * Getting paginated favorite vybes
         */
        $paginatedFavoriteVybes = paginateCollection(
            $favoriteVybes,
            $request->input('per_page')
        );

        return $this->setPagination($paginatedFavoriteVybes)->respondWithSuccess(
            $this->transformCollection(
                $paginatedFavoriteVybes,
                new FavoriteVybeTransformer(
                    $this->vybeImageRepository->getByVybes(
                        new Collection($paginatedFavoriteVybes->items())
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection($paginatedFavoriteVybes->items())
                    ),
                    $this->userAvatarRepository->getByUsers(
                        $this->userService->getByVybes(
                            new Collection($paginatedFavoriteVybes->items())
                        )
                    )
                )
            ) + [
                'vybes_count' => $favoriteVybesCount
            ], trans('validations/api/general/dashboard/vybe/favorite/getMoreFavoriteVybes.result.success')
        );
    }
}
