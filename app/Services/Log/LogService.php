<?php

namespace App\Services\Log;

use App\Exceptions\LogException;
use App\Exceptions\MicroserviceException;
use App\Lists\User\Balance\Type\UserBalanceTypeListItem;
use App\Microservices\Log\LogMicroservice;
use App\Microservices\Log\Responses\UserWalletTransactionLogResponse;
use App\Models\MongoDb\WithdrawalRequest;
use App\Models\MySql\Order\Order;
use App\Models\MySql\Order\OrderInvoice;
use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Models\MySql\Sale;
use App\Models\MySql\Tip\TipInvoice;
use App\Models\MySql\User\UserBalance;
use App\Services\Log\Interfaces\LogServiceInterface;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class LogService
 *
 * @package App\Services\Log
 */
class LogService implements LogServiceInterface
{
    /**
     * @var LogMicroservice
     */
    protected LogMicroservice $logMicroservice;

    /**
     * LogService constructor
     */
    public function __construct()
    {
        /** @var LogMicroservice logMicroservice */
        $this->logMicroservice = new LogMicroservice();
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addFundsReceiptLog(
        AddFundsReceipt $addFundsReceipt,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse
    {
        /**
         * Creating add funds receipt log
         */
        return $this->logMicroservice->addFundsReceiptLog(
            $addFundsReceipt->user,
            $userBalanceTypeListItem,
            $code,
            $addFundsReceipt->amount,
            $userBalance->amount,
            $this->getData(
                'receipt',
                $addFundsReceipt->id,
                'AF'
            )
        );
    }

    /**
     * @param OrderInvoice $orderInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     *
     * @throws GuzzleException
     * @throws LogException
     */
    public function addInvoiceForBuyerLog(
        OrderInvoice $orderInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse
    {
        try {

            /**
             * Creating invoice for buyer log
             */
            return $this->logMicroservice->addInvoiceForBuyerLog(
                $orderInvoice->order->buyer,
                $userBalanceTypeListItem,
                $code,
                round(
                    array_sum(
                        $orderInvoice->items
                            ->pluck('amount_total')
                            ->values()
                            ->toArray()
                    ), 2
                ),
                $userBalance->amount,
                [
                    ...$this->getData(
                        'invoice',
                        $orderInvoice->id,
                        'IB'
                    ),
                    ...$this->getData(
                        'order',
                        $orderInvoice->order->id,
                        'OR'
                    )
                ]
            );
        } catch (Exception $exception) {
            throw new LogException(
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Sale $sale
     * @param OrderInvoice $orderInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     *
     * @throws GuzzleException
     * @throws LogException
     */
    public function addInvoiceForSellerLog(
        Sale $sale,
        OrderInvoice $orderInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse
    {
        try {

            /**
             * Creating invoice for seller log
             */
            return $this->logMicroservice->addInvoiceForSellerLog(
                $sale->seller,
                $userBalanceTypeListItem,
                $code,
                round(
                    array_sum(
                        $orderInvoice->items
                            ->pluck('amount_total')
                            ->values()
                            ->toArray()
                    ), 2
                ),
                $userBalance->pending_amount,
                $userBalance->amount,
                [
                    ...$this->getData(
                        'invoice',
                        $orderInvoice->id,
                        'IS'
                    ),
                    ...$this->getData(
                        'sale',
                        $sale->id,
                        'SO'
                    )
                ]
            );
        } catch (Exception $exception) {
            throw new LogException(
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderInvoice $creditInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     *
     * @throws GuzzleException
     * @throws LogException
     */
    public function addCreditInvoiceForBuyerLog(
        OrderInvoice $creditInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse
    {
        try {

            /**
             * Creating credit invoice for buyer log
             */
            return $this->logMicroservice->addCreditInvoiceForBuyerLog(
                $creditInvoice->parent
                    ->order
                    ->buyer,
                $userBalanceTypeListItem,
                $code,
                round(
                    array_sum(
                        $creditInvoice->items
                            ->pluck('amount_total')
                            ->values()
                            ->toArray()
                    ), 2
                ),
                $userBalance->amount,
                [
                    ...$this->getData(
                        'credit',
                        $creditInvoice->id,
                        'CIB'
                    ),
                    ...$this->getData(
                        'invoice',
                        $creditInvoice->parent
                            ->order
                            ->id,
                        'IB'
                    )
                ]
            );
        } catch (Exception $exception) {
            throw new LogException(
                $exception->getMessage()
            );
        }
    }

    /**
     * @param OrderInvoice $creditInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     *
     * @throws GuzzleException
     * @throws LogException
     */
    public function addCreditInvoiceForSellerLog(
        OrderInvoice $creditInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse
    {
        try {

            /**
             * Creating credit invoice for seller log
             */
            return $this->logMicroservice->addCreditInvoiceForSellerLog(
                $creditInvoice->parent
                    ->order
                    ->buyer,
                $userBalanceTypeListItem,
                $code,
                round(
                    array_sum(
                        $creditInvoice->items
                            ->pluck('amount_total')
                            ->values()
                            ->toArray()
                    ), 2
                ),
                $userBalance->pending_amount,
                $userBalance->amount,
                [
                    ...$this->getData(
                        'credit',
                        $creditInvoice->id,
                        'CIB'
                    ),
                    ...$this->getData(
                        'invoice',
                        $creditInvoice->parent
                            ->order
                            ->id,
                        'IB'
                    )
                ]
            );
        } catch (Exception $exception) {
            throw new LogException(
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TipInvoice $tipInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     *
     * @throws GuzzleException
     * @throws LogException
     */
    public function addTipInvoiceForBuyerLog(
        TipInvoice $tipInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse
    {
        try {

            /**
             * Creating tip invoice for buyer log
             */
            return $this->logMicroservice->addTipInvoiceForBuyerLog(
                $tipInvoice->tip->buyer,
                $userBalanceTypeListItem,
                $code,
                $tipInvoice->tip->amount_total,
                $userBalance->amount,
                [
                    ...$this->getData(
                        'invoice',
                        $tipInvoice->id,
                        'TIB'
                    )
                ]
            );
        } catch (Exception $exception) {
            throw new LogException(
                $exception->getMessage()
            );
        }
    }

    /**
     * @param TipInvoice $tipInvoice
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     *
     * @throws GuzzleException
     * @throws LogException
     */
    public function addTipInvoiceForSellerLog(
        TipInvoice $tipInvoice,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse
    {
        try {

            /**
             * Creating tip invoice for seller log
             */
            return $this->logMicroservice->addTipInvoiceForSellerLog(
                $tipInvoice->tip->seller,
                $userBalanceTypeListItem,
                $code,
                $tipInvoice->tip->amount_earned,
                $userBalance->pending_amount,
                $userBalance->amount,
                [
                    ...$this->getData(
                        'invoice',
                        $tipInvoice->id,
                        'TIS'
                    )
                ]
            );
        } catch (Exception $exception) {
            throw new LogException(
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Order $order
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     *
     * @throws GuzzleException
     * @throws LogException
     */
    public function addOrderOverviewLog(
        Order $order,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse
    {
        try {

            /**
             * Creating order overview log
             */
            return $this->logMicroservice->addOrderOverviewLog(
                $order->buyer,
                $userBalanceTypeListItem,
                $code,
                $order->amount_total,
                $userBalance->amount,
                [
                    ...$this->getData(
                        'order',
                        $order->id,
                        'OR'
                    )
                ]
            );
        } catch (Exception $exception) {
            throw new LogException(
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Sale $sale
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     *
     * @throws GuzzleException
     * @throws LogException
     */
    public function addSaleOverviewLog(
        Sale $sale,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse
    {
        try {

            /**
             * Creating sale overview log
             */
            return $this->logMicroservice->addSaleOverviewLog(
                $sale->seller,
                $userBalanceTypeListItem,
                $code,
                $sale->amount_total,
                $userBalance->pending_amount,
                $userBalance->amount,
                [
                    ...$this->getData(
                        'sale',
                        $sale->id,
                        'SO'
                    )
                ]
            );
        } catch (Exception $exception) {
            throw new LogException(
                $exception->getMessage()
            );
        }
    }

    /**
     * @param WithdrawalRequest $withdrawalRequest
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addWithdrawalRequestLog(
        WithdrawalRequest $withdrawalRequest,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse
    {
        /**
         * Creating withdrawal request log
         */
        return $this->logMicroservice->addWithdrawalRequestLog(
            $withdrawalRequest->user,
            $userBalanceTypeListItem,
            $code,
            $withdrawalRequest->amount,
            $userBalance->amount,
            $this->getData(
                'request',
                $withdrawalRequest->_id,
                'WS'
            )
        );
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param UserBalance $userBalance
     * @param UserBalanceTypeListItem $userBalanceTypeListItem
     * @param string $code
     *
     * @return UserWalletTransactionLogResponse|null
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function addWithdrawalReceiptLog(
        WithdrawalReceipt $withdrawalReceipt,
        UserBalance $userBalance,
        UserBalanceTypeListItem $userBalanceTypeListItem,
        string $code
    ) : ?UserWalletTransactionLogResponse
    {
        /**
         * Creating withdrawal receipt log
         */
        return $this->logMicroservice->addWithdrawalReceiptLog(
            $withdrawalReceipt->user,
            $userBalanceTypeListItem,
            $code,
            $withdrawalReceipt->amount,
            $userBalance->amount,
            $this->getData(
                'receipt',
                $withdrawalReceipt->id,
                'WS'
            )
        );
    }

    /**
     * @param string $key
     * @param mixed $id
     * @param string $prefix
     * 
     * @return array[]
     */
    private function getData(
        string $key,
        mixed $id,
        string $prefix
    ) : array
    {
        return [
            $key => [
                'id'     => $id,
                'prefix' => $prefix
            ]
        ];
    }
}