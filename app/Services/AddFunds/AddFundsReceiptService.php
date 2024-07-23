<?php

namespace App\Services\AddFunds;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusList;
use App\Lists\AddFunds\Receipt\Status\AddFundsReceiptStatusListItem;
use App\Lists\Currency\CurrencyList;
use App\Lists\Payment\Type\PaymentTypeList;
use App\Models\MySql\Receipt\AddFundsReceipt;
use App\Models\MySql\Receipt\Transaction\AddFundsTransaction;
use App\Repositories\Receipt\AddFundsReceiptRepository;
use App\Repositories\Receipt\AddFundsTransactionRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Services\AddFunds\Interfaces\AddFundsReceiptServiceInterface;
use App\Services\Payment\PayPalService;
use App\Support\Service\PayPal\TransactionResponse;
use Illuminate\Database\Eloquent\Collection;
use JsonMapper_Exception;

/**
 * Class AddFundsReceiptService
 *
 * @package App\Services\AddFunds
 */
class AddFundsReceiptService implements AddFundsReceiptServiceInterface
{
    /**
     * @var AddFundsReceiptRepository
     */
    protected AddFundsReceiptRepository $addFundsReceiptRepository;

    /**
     * @var AddFundsTransactionRepository
     */
    protected AddFundsTransactionRepository $addFundsTransactionRepository;

    /**
     * @var PayPalService
     */
    protected PayPalService $payPalService;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * AddFundsReceiptService constructor
     */
    public function __construct()
    {
        /** @var AddFundsReceiptRepository addFundsReceiptRepository */
        $this->addFundsReceiptRepository = new AddFundsReceiptRepository();

        /** @var AddFundsTransactionRepository addFundsTransactionRepository */
        $this->addFundsTransactionRepository = new AddFundsTransactionRepository();

        /** @var PayPalService payPalService */
        $this->payPalService = new PayPalService();

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     * @param string $hash
     *
     * @return bool
     */
    public function checkHash(
        AddFundsReceipt $addFundsReceipt,
        string $hash
    ) : bool
    {
        return strcmp($addFundsReceipt->hash, $hash) == 0;
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return string|null
     *
     * @throws BaseException
     */
    public function getPaymentUrl(
        AddFundsReceipt $addFundsReceipt
    ) : ?string
    {
        /**
         * Checking payment method
         */
        if ($addFundsReceipt->method->code == 'paypal') {

            /**
             * Getting PayPal payment url
             */
            return $this->payPalService->getPaymentUrl(
                PaymentTypeList::getAddFunds(),
                CurrencyList::getUsd(),
                $addFundsReceipt->id,
                $addFundsReceipt->amount,
                $addFundsReceipt->hash
            );
        }

        return null;
    }

    /**
     * @param AddFundsReceipt $addFundsReceipt
     *
     * @return AddFundsReceipt
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws JsonMapper_Exception
     */
    public function executePayment(
        AddFundsReceipt $addFundsReceipt
    ) : AddFundsReceipt
    {
        /**
         * Checking a payment method type
         */
        if ($addFundsReceipt->method->code == 'paypal') {

            /**
             * Executing add funds transactions
             */
            $transactions = $this->payPalService->executePayment(
                PaymentTypeList::getAddFunds(),
                $addFundsReceipt->id,
                $addFundsReceipt->hash
            );

            /** @var TransactionResponse $transaction */
            foreach ($transactions as $transaction) {

                /**
                 * Updating add funds receipt
                 */
                $addFundsReceipt = $this->addFundsReceiptRepository->updatePaymentFields(
                    $addFundsReceipt,
                    $transaction->transactionFee,
                    array_sum([
                        $addFundsReceipt->amount,
                        $transaction->transactionFee
                    ]),
                    $transaction->description,
                    AddFundsReceiptStatusList::getPaid()
                );

                /**
                 * Creating add funds transaction
                 */
                $this->addFundsTransactionRepository->store(
                    $addFundsReceipt,
                    $addFundsReceipt->method,
                    $transaction->id,
                    $transaction->totalAmount,
                    $transaction->transactionFee,
                    $transaction->description
                );
            }
        }

        return $this->addFundsReceiptRepository->findFullById(
            $addFundsReceipt->id
        );
    }

    /**
     * @param Collection $addFundsReceipts
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminStatusesByIds(
        Collection $addFundsReceipts
    ) : Collection
    {
        /**
         * Getting add funds receipts count
         */
        $addFundsReceiptsCounts = $this->addFundsReceiptRepository->getStatusesByIdsCount(
            $addFundsReceipts->pluck('id')
                ->values()
                ->toArray()
        );

        /**
         * Getting add funds receipt statuses
         */
        $addFundsReceiptStatuses = AddFundsReceiptStatusList::getItems();

        /** @var AddFundsReceiptStatusListItem $addFundsReceiptStatus */
        foreach ($addFundsReceiptStatuses as $addFundsReceiptStatus) {

            /**
             * Setting add funds receipt status count
             */
            $addFundsReceiptStatus->setCount(
                $addFundsReceiptsCounts->getAttribute(
                    $addFundsReceiptStatus->code
                )
            );
        }

        return $addFundsReceiptStatuses;
    }


    /**
     * @param AddFundsReceipt $addFundsReceipt
     * @param int $amount
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function isAvailableToAddPayment(
        AddFundsReceipt $addFundsReceipt,
        int $amount
    ) : bool
    {
        /**
         * Preparing add funds receipt current amount
         */
        $currentAmount = 0;

        /**
         * Checking add funds receipt transactions existence
         */
        if ($addFundsReceipt->transactions) {

            /** @var AddFundsTransaction $transaction */
            foreach ($addFundsReceipt->transactions as $transaction) {
                $currentAmount = $currentAmount + $transaction->amount;
            }
        }

        /**
         * Checking is available to make one more payment transaction
         */
        if (($currentAmount + $amount) <= $addFundsReceipt->amount) {
            if (($currentAmount + $amount) == $addFundsReceipt->amount) {

                /**
                 * Updating add funds receipt status
                 */
                $this->addFundsReceiptRepository->updateStatus(
                    $addFundsReceipt,
                    AddFundsReceiptStatusList::getPaid()
                );

                /**
                 * Increasing user buyer balance
                 */
                $this->userBalanceRepository->increaseAmount(
                    $addFundsReceipt->user->getBuyerBalance(),
                    $addFundsReceipt->amount
                );
            }

            return true;
        }

        return false;
    }
}
