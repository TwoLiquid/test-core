<?php

namespace App\Http\Controllers\Api\Admin\Csau\Unit;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\Csau\Unit\Interfaces\UnitControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\Csau\Unit\DestroyRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\GetVybesRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\IndexRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\StoreRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\ShowRequest;
use App\Http\Requests\Api\Admin\Csau\Unit\UpdateRequest;
use App\Lists\Unit\Type\UnitTypeList;
use App\Microservices\Auth\AuthMicroservice;
use App\Repositories\Unit\UnitRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Services\Unit\UnitService;
use App\Services\User\UserService;
use App\Transformers\Api\Admin\Csau\Unit\UnitWithPaginationTransformer;
use App\Transformers\Api\Admin\Csau\Unit\UnitListTransformer;
use App\Transformers\Api\Admin\Csau\Unit\UnitTransformer;
use App\Transformers\Api\Admin\Csau\Unit\VybeTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

/**
 * Class UnitController
 *
 * @package App\Http\Controllers\Api\Admin\Csau\Unit
 */
final class UnitController extends BaseController implements UnitControllerInterface
{
    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * @var UnitService
     */
    protected UnitService $unitService;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * UnitController constructor
     */
    public function __construct()
    {
        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();

        /** @var UnitService unitService */
        $this->unitService = new UnitService();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();
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
             * Checking search string existence
             */
            if ($request->input('search')) {

                /**
                 * Getting units by search
                 */
                $units = $this->unitRepository->getAllNotEventBySearchPaginated(
                    $request->input('search'),
                    $request->input('page'),
                    $request->input('per_page')
                );
            } else {

                /**
                 * Getting units
                 */
                $units = $this->unitRepository->getAllNotEventPaginated(
                    $request->input('page'),
                    $request->input('per_page')
                );
            }

            return $this->setPagination($units)->respondWithSuccess(
                $this->transformCollection($units, new UnitListTransformer),
                trans('validations/api/admin/csau/unit/index.result.success')
            );
        }

        /**
         * Checking search string existence
         */
        if ($request->input('search')) {

            /**
             * Getting units by search
             */
            $units = $this->unitRepository->getAllNotEventBySearch(
                $request->input('search')
            );
        } else {

            /**
             * Getting units
             */
            $units = $this->unitRepository->getAllNotEvent();
        }

        return $this->respondWithSuccess(
            $this->transformCollection($units, new UnitListTransformer),
            trans('validations/api/admin/csau/unit/index.result.success')
        );
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
         * Getting unit
         */
        $unit = $this->unitRepository->findFullForAdminById($id);

        /**
         * Checking unit existence
         */
        if (!$unit) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/unit/show.result.error.find')
            );
        }

        /**
         * Checking pagination is enabled
         */
        if ($request->input('paginated') === true) {
            return $this->respondWithSuccess(
                $this->transformItem(
                    $unit, 
                    new UnitWithPaginationTransformer(
                        $request->input('page'),
                        $request->input('per_page')
                    )
                ), trans('validations/api/admin/csau/unit/show.result.success')
            );
        } else {
            return $this->respondWithSuccess(
                $this->transformItem(
                    $unit, 
                    new UnitTransformer
                ), trans('validations/api/admin/csau/unit/show.result.success')
            );
        }
    }

    /**
     * @param int $id
     * @param GetVybesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function getVybes(
        int $id,
        GetVybesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting unit
         */
        $unit = $this->unitRepository->findFullForAdminById($id);

        /**
         * Checking unit existence
         */
        if (!$unit) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/unit/show.result.error.find')
            );
        }

        /**
         * Getting vybes
         */
        $vybes = $this->vybeRepository->getByUnitPaginated(
            $unit,
            $request->input('page'),
            $request->input('per_page')
        );

        return $this->setPagination($vybes)->respondWithSuccess(
            $this->transformCollection(
                $vybes,
                new VybeTransformer
            ), trans('validations/api/admin/csau/unit/getVybes.result.success')
        );
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Creating unit
         */
        $unit = $this->unitRepository->store(
            UnitTypeList::getUsual(),
            $request->input('name'),
            $request->input('duration'),
            $request->input('visible')
        );

        /**
         * Checking unit existence
         */
        if (!$unit) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/unit/store.result.error.create')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($unit, new UnitListTransformer),
            trans('validations/api/admin/csau/unit/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting unit
         */
        $unit = $this->unitRepository->findById($id);

        /**
         * Checking unit existence
         */
        if (!$unit) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/unit/update.result.error.find')
            );
        }

        /**
         * Updating unit
         */
        $unit = $this->unitRepository->update(
            $unit,
            null,
            $request->input('name'),
            $request->input('duration'),
            $request->input('visible')
        );

        return $this->respondWithSuccess(
            $this->transformItem($unit, new UnitListTransformer),
            trans('validations/api/admin/csau/unit/update.result.success')
        );
    }

    /**
     * @param int $id
     * @param DestroyRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function destroy(
        int $id,
        DestroyRequest $request
    ) : JsonResponse
    {
        /**
         * Checking admin has super rights
         */
        if (!AuthService::admin()->hasFullAccess()) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/unit/destroy.result.error.super')
            );
        }

        /**
         * Checking user password
         */
        $this->authMicroservice->checkPassword(
            $request->input('password')
        );

        /**
         * Getting unit
         */
        $unit = $this->unitRepository->findById($id);

        /**
         * Checking unit existence
         */
        if (!$unit) {
            return $this->respondWithError(
                trans('validations/api/admin/csau/unit/destroy.result.error.find')
            );
        }

        /**
         * Checking unit already has vybes
         */
        if ($unit->vybes->count()) {
            return $this->respondWithErrors([
                'vybes' => trans('validations/api/admin/csau/unit/destroy.result.error.vybes')
            ]);
        }

        /**
         * Deleting unit
         */
        $this->unitRepository->delete(
            $unit
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/csau/unit/destroy.result.success')
        );
    }
}
