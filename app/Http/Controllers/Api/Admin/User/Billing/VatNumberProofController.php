<?php

namespace App\Http\Controllers\Api\Admin\User\Billing;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\User\Billing\Interfaces\VatNumberProofControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Billing\VatNumberProof\UpdateExcludeTaxRequest;
use App\Http\Requests\Api\Admin\User\Billing\VatNumberProof\UpdateStatusRequest;
use App\Http\Requests\Api\Admin\User\Billing\VatNumberProof\UploadFilesRequest;
use App\Lists\VatNumberProof\Status\VatNumberProofStatusList;
use App\Repositories\Billing\BillingRepository;
use App\Repositories\ExcludeTaxHistory\ExcludeTaxHistoryRepository;
use App\Repositories\Media\AdminAvatarRepository;
use App\Repositories\Media\VatNumberProofDocumentRepository;
use App\Repositories\Media\VatNumberProofImageRepository;
use App\Repositories\VatNumberProof\VatNumberProofRepository;
use App\Services\Admin\AdminService;
use App\Services\Auth\AuthService;
use App\Services\VatNumberProof\VatNumberProofService;
use App\Transformers\Api\Admin\User\Billing\VatNumberProof\VatNumberProofTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

/**
 * Class VatNumberProofController
 *
 * @package App\Http\Controllers\Api\Admin\User\Billing
 */
class VatNumberProofController extends BaseController implements VatNumberProofControllerInterface
{
    /**
     * @var AdminService
     */
    protected AdminService $adminService;

    /**
     * @var AdminAvatarRepository
     */
    protected AdminAvatarRepository $adminAvatarRepository;

    /**
     * @var BillingRepository
     */
    protected BillingRepository $billingRepository;

    /**
     * @var ExcludeTaxHistoryRepository
     */
    protected ExcludeTaxHistoryRepository $excludeTaxHistoryRepository;

    /**
     * @var VatNumberProofRepository
     */
    protected VatNumberProofRepository $vatNumberProofRepository;

    /**
     * @var VatNumberProofService
     */
    protected VatNumberProofService $vatNumberProofService;

    /**
     * @var VatNumberProofImageRepository
     */
    protected VatNumberProofImageRepository $vatNumberProofImageRepository;

    /**
     * @var VatNumberProofDocumentRepository
     */
    protected VatNumberProofDocumentRepository $vatNumberProofDocumentRepository;

    /**
     * VatNumberProofController constructor
     */
    public function __construct()
    {
        /** @var AdminService adminService */
        $this->adminService = new AdminService();

        /** @var AdminAvatarRepository adminAvatarRepository */
        $this->adminAvatarRepository = new AdminAvatarRepository();

        /** @var BillingRepository billingRepository */
        $this->billingRepository = new BillingRepository();

        /** @var ExcludeTaxHistoryRepository excludeTaxHistoryRepository */
        $this->excludeTaxHistoryRepository = new ExcludeTaxHistoryRepository();

        /** @var VatNumberProofRepository vatNumberProofRepository */
        $this->vatNumberProofRepository = new VatNumberProofRepository();

        /** @var VatNumberProofService vatNumberProofService */
        $this->vatNumberProofService = new VatNumberProofService();

        /** @var VatNumberProofImageRepository vatNumberProofImageRepository */
        $this->vatNumberProofImageRepository = new VatNumberProofImageRepository();

        /** @var VatNumberProofDocumentRepository vatNumberProofDocumentRepository */
        $this->vatNumberProofDocumentRepository = new VatNumberProofDocumentRepository();
    }

    /**
     * @param string $vatNumberProofId
     * @param UploadFilesRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function uploadFiles(
        string $vatNumberProofId,
        UploadFilesRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vat number proof
         */
        $vatNumberProof = $this->vatNumberProofRepository->findById(
            $vatNumberProofId
        );

        /**
         * Checking vat number proof existence
         */
        if (!$vatNumberProof) {
            return $this->respondWithError(
                trans('validations/api/admin/user/billing/vatNumberProof/uploadFiles.result.error.find.vatNumberProof')
            );
        }

        /**
         * Checking vat number proof upload files existence
         */
        if ($request->input('vat_number_proof_files')) {

            /**
             * Creating vat number proof images
             */
            $this->vatNumberProofService->uploadFiles(
                $vatNumberProof->billing,
                $vatNumberProof,
                $request->input('vat_number_proof_files')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vatNumberProof, new VatNumberProofTransformer(
                $this->adminAvatarRepository->getByAdmins(
                    $this->adminService->getByVatNumberProofs(
                        new Collection([$vatNumberProof])
                    )
                ),
                $this->vatNumberProofImageRepository->getByVatNumberProofs(
                    new Collection([$vatNumberProof])
                ),
                $this->vatNumberProofDocumentRepository->getByVatNumberProofs(
                    new Collection([$vatNumberProof])
                )
            )),
            trans('validations/api/admin/user/billing/vatNumberProof/uploadFiles.result.success')
        );
    }

    /**
     * @param string $vatNumberProofId
     * @param UpdateStatusRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updateStatus(
        string $vatNumberProofId,
        UpdateStatusRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vat number proof
         */
        $vatNumberProof = $this->vatNumberProofRepository->findFullById(
            $vatNumberProofId
        );

        /**
         * Checking vat number proof existence
         */
        if (!$vatNumberProof) {
            return $this->respondWithError(
                trans('validations/api/admin/user/billing/vatNumberProof/updateStatus.result.error.find')
            );
        }

        /**
         * Getting data
         */
        $vatNumberProofStatusListItem = VatNumberProofStatusList::getItem(
            $request->input('vat_number_proof_status_id')
        );

        /**
         * Updating vat number proof status
         */
        $this->vatNumberProofRepository->updateStatus(
            $vatNumberProof,
            $vatNumberProofStatusListItem
        );

        /**
         * Checking vat number proof status
         */
        if ($vatNumberProofStatusListItem->isInactive()) {

            /**
             * Updating vat number proof exclude tax
             */
            $this->vatNumberProofRepository->updateExcludeTax(
                $vatNumberProof,
                false
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vatNumberProof, new VatNumberProofTransformer(
                $this->adminAvatarRepository->getByAdmins(
                    $this->adminService->getByVatNumberProofs(
                        new Collection([$vatNumberProof])
                    )
                ),
                $this->vatNumberProofImageRepository->getByVatNumberProofs(
                    new Collection([$vatNumberProof])
                ),
                $this->vatNumberProofDocumentRepository->getByVatNumberProofs(
                    new Collection([$vatNumberProof])
                )
            )),
            trans('validations/api/admin/user/billing/vatNumberProof/updateStatus.result.success')
        );
    }

    /**
     * @param string $vatNumberProofId
     * @param UpdateExcludeTaxRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function updateExcludeTax(
        string $vatNumberProofId,
        UpdateExcludeTaxRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vat number proof
         */
        $vatNumberProof = $this->vatNumberProofRepository->findById(
            $vatNumberProofId
        );

        /**
         * Checking vat number proof existence
         */
        if (!$vatNumberProof) {
            return $this->respondWithError(
                trans('validations/api/admin/user/billing/vatNumberProof/updateExcludeTax.result.error.find')
            );
        }

        /**
         * Updating vat number proof exclude tax
         */
        $this->vatNumberProofRepository->updateExcludeTax(
            $vatNumberProof,
            $request->input('exclude_tax')
        );

        /**
         * Creating exclude tax history
         */
        $this->excludeTaxHistoryRepository->store(
            $vatNumberProof,
            AuthService::admin(),
            $request->input('exclude_tax')
        );

        return $this->respondWithSuccess(
            $this->transformItem($vatNumberProof, new VatNumberProofTransformer(
                $this->adminAvatarRepository->getByAdmins(
                    $this->adminService->getByVatNumberProofs(
                        new Collection([$vatNumberProof])
                    )
                ),
                $this->vatNumberProofImageRepository->getByVatNumberProofs(
                    new Collection([$vatNumberProof])
                ),
                $this->vatNumberProofDocumentRepository->getByVatNumberProofs(
                    new Collection([$vatNumberProof])
                )
            )),
            trans('validations/api/admin/user/billing/vatNumberProof/updateExcludeTax.result.success')
        );
    }
}
