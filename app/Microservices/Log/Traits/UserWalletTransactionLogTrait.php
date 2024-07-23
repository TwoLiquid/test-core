<?php

namespace App\Microservices\Log\Traits;

use App\Exceptions\MicroserviceException;
use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use App\Microservices\Log\Responses\UserWalletTransactionLogCollectionResponse;
use App\Microservices\Log\Responses\UserWalletTransactionLogResponse;
use App\Models\MySql\User\User;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

/**
 * Trait UserWalletTransactionLogTrait
 *
 * @package App\Microservices\Log\Traits
 */
trait UserWalletTransactionLogTrait
{
    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addFundsReceiptLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/add/funds/receipts', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addInvoiceForBuyerLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/buyer/invoices', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $pendingBalance
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addInvoiceForSellerLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $pendingBalance,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/seller/invoices', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'pending_balance' => $pendingBalance,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addCreditInvoiceForBuyerLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/buyer/credit/invoices', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $pendingBalance
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addCreditInvoiceForSellerLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $pendingBalance,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/seller/credit/invoices', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'pending_balance' => $pendingBalance,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addTipInvoiceForBuyerLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/buyer/tip/invoices', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $pendingBalance
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addTipInvoiceForSellerLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $pendingBalance,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/seller/tip/invoices', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'pending_balance' => $pendingBalance,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addOrderItemLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/order/items', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addOrderOverviewLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/order/overviews', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $pendingBalance
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addSaleOverviewLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $pendingBalance,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/sale/overviews', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'pending_balance' => $pendingBalance,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addWithdrawalRequestLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/withdrawal/requests', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     * @param float $amount
     * @param float $balance
     * @param array|null $data
     *
     * @return UserWalletTransactionLogResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addWithdrawalReceiptLog(
        User $user,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code,
        float $amount,
        float $balance,
        ?array $data
    ) : UserWalletTransactionLogResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions/withdrawal/receipts', [
                    'form_params' => [
                        'balance_type_id' => $userBalanceTypeListItem->id,
                        'code'            => $code,
                        'amount'          => $amount,
                        'balance'         => $balance,
                        'data'            => $data
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogResponse(
                $responseData->log,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserBalanceTypeListItem|null $userBalanceTypeListItem
     * @param int|null $page
     * @param int|null $perPage
     * @param string|null $search
     * @param string|null $dateFrom
     * @param string|null $dateTo
     *
     * @return UserWalletTransactionLogCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getUserWalletTransactionLogs(
        User $user,
        ?UserBalanceTypeListItem $userBalanceTypeListItem = null,
        ?int $page = null,
        ?int $perPage = null,
        ?string $search = null,
        ?string $dateFrom = null,
        ?string $dateTo = null
    ) : UserWalletTransactionLogCollectionResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/log/user/' . $user->auth_id . '/wallet/transactions', [
                    'query' => [
                        'balance_type_id' => $userBalanceTypeListItem->id ?? null,
                        'page'            => $page,
                        'per_page'        => $perPage,
                        'search'          => $search,
                        'date_from'       => $dateFrom,
                        'date_to'         => $dateTo
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserWalletTransactionLogCollectionResponse(
                $responseData->logs,
                (array) $responseData->pagination,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/log/userWalletTransactionLog.' . __FUNCTION__)
            );
        }
    }
}