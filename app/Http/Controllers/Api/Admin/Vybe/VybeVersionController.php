<?php

namespace App\Http\Controllers\Api\Admin\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\Admin\Vybe\Interfaces\VybeVersionControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Repositories\Media\VybeImageRepository;
use App\Repositories\Media\VybeVideoRepository;
use App\Repositories\Vybe\VybeVersionRepository;
use App\Services\Vybe\VybeVersionService;
use App\Transformers\Api\Admin\Vybe\Version\VybeVersionTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeVersionController
 *
 * @package App\Http\Controllers\Api\Admin\Vybe
 */
final class VybeVersionController extends BaseController implements VybeVersionControllerInterface
{
    /**
     * @var VybeVersionRepository
     */
    protected VybeVersionRepository $vybeVersionRepository;

    /**
     * @var VybeVersionService
     */
    protected VybeVersionService $vybeVersionService;

    /**
     * @var VybeImageRepository
     */
    protected VybeImageRepository $vybeImageRepository;

    /**
     * @var VybeVideoRepository
     */
    protected VybeVideoRepository $vybeVideoRepository;

    /**
     * VybeVersionController constructor
     */
    public function __construct()
    {
        /** @var VybeVersionRepository vybeVersionRepository */
        $this->vybeVersionRepository = new VybeVersionRepository();

        /** @var VybeVersionService vybeVersionService */
        $this->vybeVersionService = new VybeVersionService();

        /** @var VybeImageRepository vybeImageRepository */
        $this->vybeImageRepository = new VybeImageRepository();

        /** @var VybeVideoRepository vybeVideoRepository */
        $this->vybeVideoRepository = new VybeVideoRepository();
    }

    /**
     * @param string $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        string $id
    ) : JsonResponse
    {
        /**
         * Getting vybe version
         */
        $vybeVersion = $this->vybeVersionRepository->findById($id);

        /**
         * Checking vybe version existence
         */
        if (!$vybeVersion) {
            return $this->respondWithError(
                trans('validations/api/admin/vybe/version/show.result.error.find')
            );
        }

        /**
         * Getting a previous vybe version
         */
        $previousVybeVersion = $this->vybeVersionService->getPreviousVersion(
            $vybeVersion
        );

        return $this->respondWithSuccess(
            $this->transformItem([],
                new VybeVersionTransformer(
                    $vybeVersion,
                    $previousVybeVersion,
                    $this->vybeImageRepository->getByVybes(
                        new Collection($vybeVersion)
                    ),
                    $this->vybeVideoRepository->getByVybes(
                        new Collection($vybeVersion)
                    ),
                    $previousVybeVersion ? $this->vybeImageRepository->getByVybes(
                        new Collection($previousVybeVersion)
                    ) : null,
                    $previousVybeVersion ? $this->vybeVideoRepository->getByVybes(
                        new Collection($previousVybeVersion)
                    ) : null,
                )
            ), trans('validations/api/admin/vybe/version/show.result.success')
        );
    }
}
