<?php

namespace App\Http\Controllers\Api\Admin\General\IpRegistrationList;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Admin\General\IpRegistrationList\Interfaces\IpRegistrationListControllerInterface;
use App\Http\Requests\Api\Admin\General\IpRegistrationList\IndexRequest;
use App\Repositories\IpRegistrationList\IpRegistrationListRepository;
use App\Transformers\Api\Admin\General\IpRegistrationList\IpRegistrationListPageTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class IpRegistrationListController
 *
 * @package App\Http\Controllers\Api\Admin\General\IpRegistrationList
 */
final class IpRegistrationListController extends BaseController implements IpRegistrationListControllerInterface
{
    /**
     * @var IpRegistrationListRepository
     */
    protected IpRegistrationListRepository $ipRegistrationListRepository;

    /**
     * IpRegistrationListController constructor
     */
    public function __construct()
    {
        /** @var IpRegistrationListRepository ipRegistrationListRepository */
        $this->ipRegistrationListRepository = new IpRegistrationListRepository();
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
         * Getting an ip registration list with pagination
         */
        $ipRegistrationList = $this->ipRegistrationListRepository->getAllPaginatedFiltered(
            $request->input('registration_date_from'),
            $request->input('registration_date_to'),
            $request->input('ip_address'),
            $request->input('username'),
            $request->input('name'),
            $request->input('statuses_ids'),
            $request->input('location'),
            $request->input('vpn'),
            $request->input('duplicates'),
            $request->input('per_page'),
            $request->input('page')
        );

        return $this->setPagination($ipRegistrationList)->respondWithSuccess(
            $this->transformItem([],
                new IpRegistrationListPageTransformer(
                    new Collection($ipRegistrationList->items())
                )
            )['ip_registration_list_page'],
            trans('validations/api/admin/general/ipRegistrationList/index.result.success')
        );
    }
}
