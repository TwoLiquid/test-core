<?php

namespace App\Http\Controllers\Api\Admin\User\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\User\Vybe\Interfaces\VybeControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Vybe\IndexRequest;
use App\Repositories\User\UserRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Repositories\Vybe\VybeSettingRepository;
use App\Settings\Vybe\HandlingFeesSetting;
use App\Transformers\Api\Admin\User\Vybe\VybeTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeController
 *
 * @package App\Http\Controllers\Api\Admin\User\Vybe
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
     * @var VybeSettingRepository
     */
    protected VybeSettingRepository $vybeSettingRepository;

    /**
     * VybeController constructor
     */
    public function __construct()
    {
        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var VybeSettingRepository vybeSettingRepository */
        $this->vybeSettingRepository = new VybeSettingRepository();
    }

    /**
     * @param int $id
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        int $id,
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById($id);

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/vybe/index.result.error.find')
            );
        }

        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting paginated users
             */
            $vybes = $this->vybeRepository->getForAdminByUserPaginated(
                $user,
                $request->input('page'),
                $request->input('per_page')
            );

            return $this->setPagination($vybes)->respondWithSuccess(
                $this->transformCollection($vybes, new VybeTransformer),
                trans('validations/api/admin/user/vybe/index.result.success')
            );
        }

        /**
         * Getting users
         */
        $vybes = $this->vybeRepository->getForAdminByUser(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformCollection($vybes, new VybeTransformer),
            trans('validations/api/admin/user/vybe/index.result.success')
        );
    }
}
