<?php

namespace App\Http\Controllers\Api\General\Profile;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Profile\Interfaces\VybeControllerInterface;
use App\Http\Requests\Api\General\Profile\Vybe\ShowRequest;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\Vybe\VybeService;
use App\Transformers\Api\General\Profile\Vybe\VybeTransformer;
use App\Transformers\Api\General\Profile\Vybe\VybeAgeLimitTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeController
 *
 * @package App\Http\Controllers\Api\General\Profile
 */
final class VybeController extends BaseController implements VybeControllerInterface
{
    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

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
        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

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
     * @param ShowRequest $request
     * @param string $username
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        ShowRequest $request,
        string $username,
        int $id
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
            return $this->respondWithError(
                trans('validations/api/general/profile/vybe/show.result.error.find.user')
            );
        }

        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findById($id);

        /**
         * Checking vybe existence
         */
        if (!$vybe) {
            return $this->respondWithError(
                trans('validations/api/general/profile/vybe/show.result.error.find.vybe')
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
                trans('validations/api/general/profile/vybe/show.result.error.belongsToUser')
            );
        }

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
                ), trans('validations/api/general/profile/vybe/show.result.error.vybeAgeLimit'),
                403
            );
        }

        /**
         * Checking is vybe private
         */
        if (!$vybe->getAccess()->isPrivate()) {
            return $this->respondWithError(
                trans('validations/api/general/profile/vybe/show.result.error.private')
            );
        }

        /**
         * Checking vybe access password
         */
        if (!$this->vybeService->checkAccessPassword(
            $vybe,
            $request->input('access_password')
        )) {
            return $this->respondWithErrors([
                'access_password' => trans('validations/api/general/profile/vybe/show.result.error.access')
            ]);
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybe, new VybeTransformer(
                $this->vybeImageRepository->getByVybes(
                    new Collection([$vybe])
                ),
                $this->vybeVideoRepository->getByVybes(
                    new Collection([$vybe])
                )
            )), trans('validations/api/general/profile/vybe/show.result.success')
        );
    }
}
