<?php

namespace App\Http\Controllers\Api\Admin\User\Billing;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\Admin\User\Billing\Interfaces\BillingControllerInterface;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Admin\User\Billing\UpdateRequest;
use App\Repositories\Billing\BillingRepository;
use App\Repositories\Media\AdminAvatarRepository;
use App\Repositories\Media\VatNumberProofDocumentRepository;
use App\Repositories\Media\VatNumberProofImageRepository;
use App\Repositories\PhoneCode\PhoneCodeRepository;
use App\Repositories\Place\CountryPlaceRepository;
use App\Repositories\Place\RegionPlaceRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\VatNumberProof\VatNumberProofRepository;
use App\Services\Admin\AdminService;
use App\Services\Auth\AuthService;
use App\Services\Billing\BillingService;
use App\Services\VatNumberProof\VatNumberProofService;
use App\Transformers\Api\Admin\User\Billing\BillingPageTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

/**
 * Class BillingController
 * 
 * @package App\Http\Controllers\Api\Admin\User\Billing
 */
final class BillingController extends BaseController implements BillingControllerInterface
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
     * @var BillingService
     */
    protected BillingService $billingService;

    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * @var PhoneCodeRepository
     */
    protected PhoneCodeRepository $phoneCodeRepository;

    /**
     * @var RegionPlaceRepository
     */
    protected RegionPlaceRepository $regionPlaceRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

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
     * BillingController constructor
     */
    public function __construct()
    {
        /** @var AdminService adminService */
        $this->adminService = new AdminService();

        /** @var AdminAvatarRepository adminAvatarRepository */
        $this->adminAvatarRepository = new AdminAvatarRepository();

        /** @var BillingRepository billingRepository */
        $this->billingRepository = new BillingRepository();

        /** @var BillingService billingService */
        $this->billingService = new BillingService();

        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var PhoneCodeRepository phoneCodeRepository */
        $this->phoneCodeRepository = new PhoneCodeRepository();

        /** @var RegionPlaceRepository regionPlaceRepository */
        $this->regionPlaceRepository = new RegionPlaceRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();

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
     * @param int $userId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        int $userId
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findById(
            $userId
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/admin/user/billing/index.result.error.request.find')
            );
        }

        /**
         * Getting user billing
         */
        $billing = $this->billingRepository->findByUser(
            $user
        );

        /**
         * Getting vat number proofs
         */
        $vatNumberProofs = $this->vatNumberProofRepository->getByBilling(
            $billing
        );

        return $this->respondWithSuccess(
            $this->transformItem([],
                new BillingPageTransformer(
                    $billing,
                    $vatNumberProofs,
                    $this->adminAvatarRepository->getByAdmins(
                        $this->adminService->getByVatNumberProofs(
                            $vatNumberProofs
                        )
                    ),
                    $this->vatNumberProofImageRepository->getByVatNumberProofs(
                        $vatNumberProofs
                    ),
                    $this->vatNumberProofDocumentRepository->getByVatNumberProofs(
                        $vatNumberProofs
                    )
                )
            )['billing_page'],
            trans('validations/api/admin/user/billing/index.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function update(
        int $id,
        UpdateRequest $request
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
                trans('validations/api/admin/user/billing/update.result.error.find')
            );
        }

        /**
         * Getting country place
         */
        $countryPlace = $this->countryPlaceRepository->findByPlaceId(
            $request->input('country_place_id')
        );

        /**
         * Getting region place
         */
        $regionPlace = $this->regionPlaceRepository->findByPlaceId(
            $request->input('region_place_id')
        );

        /**
         * Getting phone code country place
         */
        $phoneCodeCountryPlace = $this->countryPlaceRepository->findByPlaceId(
            $request->input('phone_code_country_place_id')
        );

        /**
         * Updating user billing
         */
        $billing = $this->billingRepository->update(
            $user->billing,
            $countryPlace,
            $regionPlace,
            $phoneCodeCountryPlace,
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('city'),
            $request->input('postal_code'),
            $request->input('address'),
            $request->input('phone'),
            $request->input('business_info'),
            $request->input('company_name'),
            $request->input('vat_number')
        );

        /**
         * Getting vat number proof for actual billing
         */
        $vatNumberProof = $this->vatNumberProofRepository->findByBilling(
            $billing
        );

        /**
         * Checking vat number proof existence
         */
        if (!$vatNumberProof) {

            /**
             * Checking vat number proof files existence
             */
            if ($request->input('vat_number_proof_files')) {

                /**
                 * Creating vat number proof
                 */
                $vatNumberProof = $this->vatNumberProofRepository->store(
                    $billing,
                    $countryPlace,
                    $request->input('company_name'),
                    $request->input('vat_number'),
                    AuthService::admin()
                );

                if (!$vatNumberProof) {
                    return $this->respondWithError(
                        trans('validations/api/admin/user/billing/update.result.error.vatNumberProof.create')
                    );
                }

                /**
                 * Creating vat number proof files
                 */
                $this->vatNumberProofService->uploadFiles(
                    $billing,
                    $vatNumberProof,
                    $request->input('vat_number_proof_files')
                );
            }
        }

        /**
         * Getting billing
         */
        $billing = $this->billingRepository->findByUser(
            $user
        );

        /**
         * Getting vat number proofs
         */
        $vatNumberProofs = $this->vatNumberProofRepository->getByBilling(
            $billing
        );

        return $this->respondWithSuccess(
            $this->transformItem([],
                new BillingPageTransformer(
                    $billing,
                    $vatNumberProofs,
                    $this->adminAvatarRepository->getByAdmins(
                        $this->adminService->getByVatNumberProofs(
                            $vatNumberProofs
                        )
                    ),
                    $this->vatNumberProofImageRepository->getByVatNumberProofs(
                        $vatNumberProofs
                    ),
                    $this->vatNumberProofDocumentRepository->getByVatNumberProofs(
                        $vatNumberProofs
                    )
                )
            )['billing_page'],
            trans('validations/api/admin/user/billing/update.result.success')
        );
    }
}
