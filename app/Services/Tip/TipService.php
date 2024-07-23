<?php

namespace App\Services\Tip;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Lists\Currency\CurrencyList;
use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Lists\Payment\Type\PaymentTypeList;
use App\Models\MySql\Billing;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\Tip\Tip;
use App\Models\MySql\Tip\TipInvoice;
use App\Models\MySql\User\User;
use App\Repositories\Tip\TipInvoiceRepository;
use App\Repositories\Tip\TipRepository;
use App\Repositories\Tip\TipTransactionRepository;
use App\Repositories\User\UserBalanceRepository;
use App\Services\Payment\PayPalService;
use App\Services\Tip\Interfaces\TipServiceInterface;
use App\Settings\User\HandlingFeesSetting as UserHandlingFeesSetting;
use App\Settings\Vybe\HandlingFeesSetting as VybeHandlingFeesSetting;
use App\Support\Service\PayPal\TransactionResponse;
use JsonMapper_Exception;

/**
 * Class TipService
 *
 * @package App\Services\Tip
 */
class TipService implements TipServiceInterface
{
    /**
     * @var PayPalService
     */
    protected PayPalService $payPalService;

    /**
     * @var TipInvoiceRepository
     */
    protected TipInvoiceRepository $tipInvoiceRepository;

    /**
     * @var TipRepository
     */
    protected TipRepository $tipRepository;

    /**
     * @var TipTransactionRepository
     */
    protected TipTransactionRepository $tipTransactionRepository;

    /**
     * @var UserHandlingFeesSetting
     */
    protected UserHandlingFeesSetting $userHandlingFeesSetting;

    /**
     * @var UserBalanceRepository
     */
    protected UserBalanceRepository $userBalanceRepository;

    /**
     * @var VybeHandlingFeesSetting
     */
    protected VybeHandlingFeesSetting $vybeHandlingFeesSetting;

    /**
     * TipService constructor
     */
    public function __construct()
    {
        /** @var PayPalService payPalService */
        $this->payPalService = new PayPalService();

        /** @var TipInvoiceRepository tipInvoiceRepository */
        $this->tipInvoiceRepository = new TipInvoiceRepository();

        /** @var TipRepository tipRepository */
        $this->tipRepository = new TipRepository();

        /** @var TipTransactionRepository tipTransactionRepository */
        $this->tipTransactionRepository = new TipTransactionRepository();

        /** @var UserHandlingFeesSetting userHandlingFeesSetting */
        $this->userHandlingFeesSetting = new UserHandlingFeesSetting();

        /** @var UserBalanceRepository userBalanceRepository */
        $this->userBalanceRepository = new UserBalanceRepository();

        /** @var VybeHandlingFeesSetting vybeHandlingFeesSetting */
        $this->vybeHandlingFeesSetting = new VybeHandlingFeesSetting();
    }

    /**
     * @param User $user
     * @param PaymentMethod $paymentMethod
     * @param OrderItem $orderItem
     * @param float $amount
     * @param string|null $comment
     *
     * @return Tip
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function createTip(
        User $user,
        PaymentMethod $paymentMethod,
        OrderItem $orderItem,
        float $amount,
        ?string $comment
    ) : Tip
    {
        /**
         * Getting amount tax
         */
        $amountTax = $this->getAmountTax(
            $user->billing,
            $amount
        );

        /**
         * Getting payment fee
         */
        $paymentFee = $this->getPaymentFee(
            $paymentMethod,
            $amount
        );

        /**
         * Getting payment fee tax
         */
        $paymentFeeTax = $this->getAmountTax(
            $user->billing,
            $paymentFee
        );

        /**
         * Setting vybe settings
         */
        $this->vybeHandlingFeesSetting->setVybe(
            $orderItem->vybe
        );

        /**
         * Getting vybe setting tip handling fee
         */
        $tipHandlingFee = $this->vybeHandlingFeesSetting->getVybeTippingHandlingFee();

        /**
         * Checking tip handling fee existence
         */
        if (!$tipHandlingFee) {

            /**
             * Setting user settings
             */
            $this->userHandlingFeesSetting->setUser(
                $orderItem->seller
            );

            /**
             * Getting user setting tip handling fee
             */
            $tipHandlingFee = $this->userHandlingFeesSetting->getTippingHandlingFee();
        }

        /**
         * Counting all about user / vybe handling fee
         */
        $handlingFee = round(
            $amount * ($tipHandlingFee / 100),
            2
        );

        /**
         * Getting amount earned
         */
        $amountEarned = $amount - $handlingFee;

        /**
         * Getting amount total
         */
        $amountTotal = array_sum([
            $amount,
            $amountTax,
            $paymentFee,
            $paymentFeeTax
        ]);

        /**
         * Creating tip
         */
        $tip = $this->tipRepository->store(
            $orderItem,
            $paymentMethod,
            $user,
            $orderItem->seller,
            $amount,
            $amountEarned,
            $amountTax,
            $amountTotal,
            $handlingFee,
            $paymentFee,
            $paymentFeeTax,
            $comment
        );

        /**
         * Checking tip existence
         */
        if (!$tip) {
            throw new BaseException(
                trans('exceptions/service/tip.' . __FUNCTION__ . '.tip'),
                null,
                422
            );
        }

        /**
         * Creating tip buyer invoice
         */
        $tipBuyerInvoice = $this->tipInvoiceRepository->store(
            $tip,
            InvoiceTypeList::getTipBuyer(),
            InvoiceStatusList::getUnpaid()
        );

        /**
         * Checking tip buyer existence
         */
        if (!$tipBuyerInvoice) {
            throw new BaseException(
                trans('exceptions/service/tip.' . __FUNCTION__ . '.tipBuyerInvoice'),
                null,
                422
            );
        }

        /**
         * Creating tip seller invoice
         */
        $tipSellerInvoice = $this->tipInvoiceRepository->store(
            $tip,
            InvoiceTypeList::getTipSeller(),
            InvoiceStatusList::getUnpaid()
        );

        /**
         * Checking tip seller existence
         */
        if (!$tipSellerInvoice) {
            throw new BaseException(
                trans('exceptions/service/tip.' . __FUNCTION__ . '.tipSellerInvoice'),
                null,
                422
            );
        }

        return $this->tipRepository->findFullById(
            $tip->id
        );
    }

    /**
     * @param Tip $tip
     * @param string $hash
     *
     * @return bool
     */
    public function checkHash(
        Tip $tip,
        string $hash
    ) : bool
    {
        return strcmp($tip->hash, $hash) == 0;
    }

    /**
     * @param Billing $billing
     * @param float $amount
     *
     * @return float
     */
    public function getAmountTax(
        Billing $billing,
        float $amount
    ) : float
    {
        /**
         * Preparing amount tax variable
         */
        $amountTax = 0;

        /**
         * Getting tax rule country
         */
        $taxRuleCountry = $billing->countryPlace ?
            $billing->countryPlace->taxRuleCountry :
            null;

        /**
         * Getting a tax rule region
         */
        $taxRuleRegion = $billing->regionPlace ?
            $billing->regionPlace->taxRuleRegion :
            null;

        /**
         * Checking tax rule country existence
         */
        if ($taxRuleCountry) {

            /**
             * Counting amount tax
             */
            $amountTax += round(
                $amount / 100 * $taxRuleCountry->tax_rate,
                2
            );
        }

        /**
         * Checking a tax rule region existence
         */
        if ($taxRuleRegion) {

            /**
             * Counting amount tax
             */
            $amountTax += round(
                $amount / 100 * $taxRuleRegion->tax_rate,
                2
            );
        }

        return round(
            $amountTax,
            2
        );
    }

    /**
     * @param PaymentMethod $paymentMethod
     * @param float $amount
     *
     * @return float
     */
    public function getPaymentFee(
        PaymentMethod $paymentMethod,
        float $amount
    ) : float
    {
        /**
         * Preparing payment fee variable
         */
        $paymentFee = 0;

        /**
         * Checking payment fee existence
         */
        if ($paymentMethod->payment_fee) {

            /**
             * Getting payment fee
             */
            $paymentFee = round(
                $amount / 100 * $paymentMethod->payment_fee,
                2
            );
        }

        return round(
            $paymentFee,
            2
        );
    }

    /**
     * @param Tip $tip
     *
     * @return string|null
     *
     * @throws BaseException
     */
    public function getPaymentUrl(
        Tip $tip
    ) : ?string
    {
        /**
         * Checking payment method
         */
        if ($tip->method->code == 'paypal') {

            /**
             * Getting PayPal payment url
             */
            return $this->payPalService->getPaymentUrl(
                PaymentTypeList::getTip(),
                CurrencyList::getUsd(),
                $tip->id,
                $tip->amount,
                $tip->hash
            );
        }

        return null;
    }

    /**
     * @param Tip $tip
     *
     * @return Tip
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws JsonMapper_Exception
     */
    public function executePayment(
        Tip $tip
    ) : Tip
    {
        /**
         * Checking a payment method type
         */
        if ($tip->method->code == 'balance') {

            /**
             * Creating order transaction
             */
            $this->tipTransactionRepository->store(
                $tip,
                $tip->method,
                null,
                $tip->amount_total,
                null,
                null
            );
        }

        /**
         * Checking a payment method type
         */
        if ($tip->method->code == 'paypal') {

            /**
             * Executing payment transaction response
             */
            $transactions = $this->payPalService->executePayment(
                PaymentTypeList::getTip(),
                $tip->id,
                $tip->hash
            );

            /** @var TransactionResponse $transaction */
            foreach ($transactions as $transaction) {

                /**
                 * Creating tip transaction
                 */
                $this->tipTransactionRepository->store(
                    $tip,
                    $tip->method,
                    $transaction->id,
                    $transaction->totalAmount,
                    $transaction->transactionFee,
                    $transaction->description
                );
            }
        }

        /**
         * Updating paid tip and tip invoices
         */
        return $this->updatePaidTip(
            $tip
        );
    }

    /**
     * @param Tip $tip
     *
     * @return bool
     */
    public function checkTipUnpaid(
        Tip $tip
    ) : bool
    {
        /** @var TipInvoice $tipInvoice */
        foreach ($tip->invoices as $tipInvoice) {

            /**
             * Checking tip invoice status
             */
            if ($tipInvoice->getStatus()->isUnpaid()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Tip $tip
     *
     * @return Tip
     *
     * @throws DatabaseException
     */
    public function updatePaidTip(
        Tip $tip
    ) : Tip
    {
        /** @var TipInvoice $tipInvoice */
        foreach ($tip->invoices as $tipInvoice) {

            /**
             * Updating tip invoice status
             */
            $this->tipInvoiceRepository->updateStatus(
                $tipInvoice,
                InvoiceStatusList::getPaid()
            );

            /**
             * Checking tip an invoice type
             */
            if ($tipInvoice->getType()->isTipBuyer()) {

                /**
                 * Decreasing user-buyer balance
                 */
                $this->userBalanceRepository->decreaseAmount(
                    $tip->buyer->getBuyerBalance(),
                    $tip->amount_total
                );

                /**
                 * Increase seller balance with earned amount
                 */
                $this->userBalanceRepository->increasePendingAmount(
                    $tip->seller->getSellerBalance(),
                    $tip->amount_earned
                );
            }
        }

        return $this->tipRepository->findFullById(
            $tip->id
        );
    }

    /**
     * @param Tip $tip
     *
     * @return Tip
     *
     * @throws DatabaseException
     */
    public function cancelPayment(
        Tip $tip
    ) : Tip
    {
        /** @var TipInvoice $tipInvoice */
        foreach ($tip->invoices as $tipInvoice) {

            /**
             * Updating tip invoice status
             */
            $this->tipInvoiceRepository->updateStatus(
                $tipInvoice,
                InvoiceStatusList::getCanceled()
            );
        }

        return $tip;
    }
}
