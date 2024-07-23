<?php

namespace App\Http\Controllers\Api\Admin\General\IpBanList;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\General\IpBanList\Interfaces\IpBanListControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\General\IpBanList\DestroyManyRequest;
use App\Http\Requests\Api\Admin\General\IpBanList\IndexRequest;
use App\Http\Requests\Api\Admin\General\IpBanList\StoreRequest;
use App\Repositories\IpBanList\IpBanListRepository;
use App\Services\IpBanList\IpBanListService;
use App\Transformers\Api\Admin\General\IpBanList\IpBanListTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class IpBanListController
 *
 * @package App\Http\Controllers\Api\Admin\General\IpBanList
 */
final class IpBanListController extends BaseController implements IpBanListControllerInterface
{
    /**
     * @var IpBanListRepository
     */
    protected IpBanListRepository $ipBanListRepository;

    /**
     * @var IpBanListService
     */
    protected IpBanListService $ipBanListService;

    /**
     * IpBanListController constructor
     */
    public function __construct()
    {
        /** @var IpBanListRepository ipBanListRepository */
        $this->ipBanListRepository = new IpBanListRepository();

        /** @var IpBanListService ipBanListService */
        $this->ipBanListService = new IpBanListService();
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
        if ($request->input('paginated') === true) {
            if ($request->input('search')) {

                /**
                 * Getting an ip ban list by search with pagination
                 */
                $ipBanList = $this->ipBanListRepository->getAllBySearchPaginated(
                    $request->input('search'),
                    $request->input('page'),
                    $request->input('per_page')
                );
            } else {

                /**
                 * Getting ip ban list
                 */
                $ipBanList = $this->ipBanListRepository->getAllPaginated(
                    $request->input('page'),
                    $request->input('per_page')
                );
            }

            return $this->setPagination($ipBanList)->respondWithSuccess(
                $this->transformCollection($ipBanList, new IpBanListTransformer),
                trans('validations/api/admin/general/ipBanList/index.result.success')
            );
        }

        if ($request->input('search')) {

            /**
             * Getting an ip ban list by search
             */
            $ipBanList = $this->ipBanListRepository->getAllBySearch(
                $request->input('search')
            );
        } else {

            /**
             * Getting ip ban list
             */
            $ipBanList = $this->ipBanListRepository->getAll();
        }

        return $this->respondWithSuccess(
            $this->transformCollection($ipBanList, new IpBanListTransformer),
            trans('validations/api/admin/general/ipBanList/index.result.success')
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
         * Getting ip ban list
         */
        $ipBanList = $this->ipBanListRepository->findById($id);

        if (!$ipBanList) {
            return $this->respondWithError(
                trans('validations/api/admin/general/ipBanList/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($ipBanList, new IpBanListTransformer),
            trans('validations/api/admin/general/ipBanList/show.result.success')
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
         * Validating ip address string
         */
        if (!$this->ipBanListService->validateIpAddressesString(
            $request->input('ip_addresses')
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/general/ipBanList/store.result.error.validate')
            );
        }

        /**
         * Creating ip address
         */
        $this->ipBanListService->storeIpAddresses(
            $request->input('ip_addresses')
        );

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/ipBanList/store.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting ip ban list
         */
        $ipBanList = $this->ipBanListRepository->findById($id);

        if (!$ipBanList) {
            return $this->respondWithError(
                trans('validations/api/admin/general/ipBanList/destroy.result.error.find')
            );
        }

        /**
         * Deleting ip ban list
         */
        if (!$this->ipBanListRepository->delete($ipBanList)) {
            return $this->respondWithError(
                trans('validations/api/admin/general/ipBanList/destroy.result.error.delete')
            );
        }

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/ipBanList/destroy.result.success')
        );
    }

    /**
     * @param DestroyManyRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroyMany(
        DestroyManyRequest $request
    ) : JsonResponse
    {
        /**
         * Deleting an ip ban list by ids
         */
        if (!$this->ipBanListRepository->deleteByIds(
            $request->input('ip_ban_list_ids')
        )) {
            return $this->respondWithError(
                trans('validations/api/admin/general/ipBanList/destroy.result.error.delete')
            );
        }

        return $this->respondWithSuccess([],
            trans('validations/api/admin/general/ipBanList/destroy.result.success')
        );
    }
}
