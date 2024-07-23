<?php

namespace App\Services\Withdrawal;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusList;
use App\Lists\Withdrawal\Receipt\Status\WithdrawalReceiptStatusListItem;
use App\Microservices\Media\MediaMicroservice;
use App\Models\MySql\Receipt\Transaction\WithdrawalTransaction;
use App\Models\MySql\Receipt\WithdrawalReceipt;
use App\Repositories\Receipt\WithdrawalReceiptRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Services\Withdrawal\Interfaces\WithdrawalReceiptServiceInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class WithdrawalReceiptService
 *
 * @package App\Services\Withdrawal
 */
class WithdrawalReceiptService implements WithdrawalReceiptServiceInterface
{
    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * @var WithdrawalReceiptRepository
     */
    protected WithdrawalReceiptRepository $withdrawalReceiptRepository;

    /**
     * WithdrawalReceiptService constructor
     */
    public function __construct()
    {
        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();

        /** @var WithdrawalReceiptRepository withdrawalReceiptRepository */
        $this->withdrawalReceiptRepository = new WithdrawalReceiptRepository();
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param int $amount
     *
     * @return bool
     */
    public function isAvailableToAddTransfer(
        WithdrawalReceipt $withdrawalReceipt,
        int $amount
    ) : bool
    {
        /**
         * Preparing withdrawal receipt current amount
         */
        $currentAmount = 0;

        /**
         * Checking withdrawal receipt transactions existence
         */
        if ($withdrawalReceipt->transactions) {

            /** @var WithdrawalTransaction $transaction */
            foreach ($withdrawalReceipt->transactions as $transaction) {
                $currentAmount = $currentAmount + $transaction->amount;
            }
        }

        /**
         * Checking is available to make one more withdrawal transaction
         */
        if (($currentAmount + $amount) <= $withdrawalReceipt->amount) {
            return true;
        }

        return false;
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param int $amount
     * 
     * @return WithdrawalReceipt
     *
     * @throws DatabaseException
     */
    public function updateTotalPaid(
        WithdrawalReceipt $withdrawalReceipt,
        int $amount
    ) : WithdrawalReceipt
    {
        /**
         * Preparing withdrawal receipt current amount
         */
        $currentAmount = 0;

        /**
         * Checking withdrawal receipt transactions existence
         */
        if ($withdrawalReceipt->transactions) {

            /** @var WithdrawalTransaction $transaction */
            foreach ($withdrawalReceipt->transactions as $transaction) {
                $currentAmount = $currentAmount + $transaction->amount;
            }
        }

        /**
         * Checking is available to make one more withdrawal transaction
         */
        if (($currentAmount + $amount) <= $withdrawalReceipt->amount) {
            if (($currentAmount + $amount) == $withdrawalReceipt->amount) {

                /**
                 * Updating withdrawal receipt status
                 */
                $withdrawalReceipt = $this->withdrawalReceiptRepository->updateStatus(
                    $withdrawalReceipt,
                    WithdrawalReceiptStatusList::getPaid()
                );

                /**
                 * Increasing user buyer balance
                 */
                $this->userBalanceRepository->decreaseAmount(
                    $withdrawalReceipt->user->getSellerBalance(),
                    $withdrawalReceipt->amount
                );
            }
        }

        return $withdrawalReceipt;
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param WithdrawalTransaction $withdrawalTransaction
     *
     * @return WithdrawalReceipt
     *
     * @throws DatabaseException
     */
    public function addAmountByTransfer(
        WithdrawalReceipt $withdrawalReceipt,
        WithdrawalTransaction $withdrawalTransaction
    ) : WithdrawalReceipt
    {
        $totalAmount = $withdrawalReceipt->amount_total + $withdrawalTransaction->amount;
        $transactionFee = $withdrawalReceipt->payment_fee + $withdrawalTransaction->transaction_fee;

        return $this->withdrawalReceiptRepository->updateTotalAmountAndPaymentFee(
            $withdrawalReceipt,
            $transactionFee,
            $totalAmount
        );
    }

    /**
     * @param WithdrawalReceipt $withdrawalReceipt
     * @param array $filesItems
     *
     * @return void
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function uploadProofFiles(
        WithdrawalReceipt $withdrawalReceipt,
        array $filesItems
    ) : void
    {
        $images = [];
        $documents = [];

        /** @var array $fileItem */
        foreach ($filesItems as $fileItem) {
            if (in_array(
                trim($fileItem['mime']),
                config('media.default.image.allowedMimes')
            )) {
                $images[] = $fileItem;
            } else {
                $documents[] = $fileItem;
            }
        }

        /**
         * Checking images existence
         */
        if (!empty($images)) {

            /**
             * Storing withdrawal receipt proof images
             */
            $this->mediaMicroservice->storeWithdrawalReceiptProofImages(
                $withdrawalReceipt,
                $images
            );
        }

        /**
         * Checking documents existence
         */
        if (!empty($documents)) {

            /**
             * Storing withdrawal receipt proof documents
             */
            $this->mediaMicroservice->storeWithdrawalReceiptProofDocuments(
                $withdrawalReceipt,
                $documents
            );
        }
    }

    /**
     * @param Collection $withdrawalReceipts
     * 
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getForAdminStatusesByIds(
        Collection $withdrawalReceipts
    ) : Collection
    {
        /**
         * Getting withdrawal receipt statuses count
         */
        $withdrawalReceiptStatusesCounts = $this->withdrawalReceiptRepository->getStatusesByIdsCount(
            $withdrawalReceipts->pluck('id')
                ->values()
                ->toArray()
        );

        /**
         * Getting withdrawal receipt statuses
         */
        $withdrawalReceiptStatuses = WithdrawalReceiptStatusList::getItems();

        /** @var WithdrawalReceiptStatusListItem $withdrawalReceiptStatus */
        foreach ($withdrawalReceiptStatuses as $withdrawalReceiptStatus) {

            /**
             * Setting withdrawal receipt status count
             */
            $withdrawalReceiptStatus->setCount(
                $withdrawalReceiptStatusesCounts->getAttribute(
                    $withdrawalReceiptStatus->code
                )
            );
        }

        return $withdrawalReceiptStatuses;
    }
}