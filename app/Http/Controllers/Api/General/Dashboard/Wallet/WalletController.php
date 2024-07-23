<?php

namespace App\Http\Controllers\Api\General\Dashboard\Wallet;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Dashboard\Wallet\Interfaces\WalletControllerInterface;
use App\Http\Requests\Api\General\Dashboard\Wallet\IndexRequest;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Microservices\Log\LogMicroservice;
use App\Services\Auth\AuthService;
use App\Transformers\Api\General\Dashboard\Wallet\WalletPageTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

/**
 * Class WalletController
 *
 * @package App\Http\Controllers\Api\General\Dashboard\Wallet
 */
final class WalletController extends BaseController implements WalletControllerInterface
{
    /**
     * @var LogMicroservice
     */
    protected LogMicroservice $logMicroservice;

    /**
     * WalletController constructor
     */
    public function __construct()
    {
        /** @var LogMicroservice logMicroservice */
        $this->logMicroservice = new LogMicroservice();
    }

    /**
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Getting a user balance type
         */
        $userBalanceTypeListItem = UserBalanceTypeList::getItem(
            $request->input('balance_type_id')
        );

        /**
         * Checking a user balance type existence
         */
        if (!$userBalanceTypeListItem) {
            return $this->respondWithError(
                trans('validations/api/general/dashboard/wallet/index.result.error')
            );
        }

        /**
         * Getting user wallet transaction logs
         */
        $userWalletTransactionLogs = $this->logMicroservice->getUserWalletTransactionLogs(
            AuthService::user(),
            $userBalanceTypeListItem,
            $request->input('page'),
            $request->input('per_page'),
            $request->input('search'),
            $request->input('date_from'),
            $request->input('date_to')
        );

        return $this->respondWithSuccess(
            $this->transformItem(
                $userWalletTransactionLogs,
                new WalletPageTransformer(
                    AuthService::user()->balances,
                    $userWalletTransactionLogs->logs,
                    $userWalletTransactionLogs->pagination
                )
            )['wallet_page'],
            trans('validations/api/general/dashboard/wallet/index.result.success')
        );
    }
}
