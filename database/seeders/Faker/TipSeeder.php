<?php

namespace Database\Seeders\Faker;

use App\Exceptions\DatabaseException;
use App\Exceptions\LogException;
use App\Lists\Invoice\Status\InvoiceStatusList;
use App\Lists\Invoice\Type\InvoiceTypeList;
use App\Lists\User\Balance\Type\UserBalanceTypeList;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use App\Repositories\Order\OrderItemRepository;
use App\Repositories\Tip\TipInvoiceRepository;
use App\Repositories\Tip\TipRepository;
use App\Repositories\Tip\TipTransactionRepository;
use App\Services\Log\LogService;
use App\Services\Tip\TipService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;
use App\Settings\User\HandlingFeesSetting as UserHandlingFeesSetting;
use App\Settings\Vybe\HandlingFeesSetting as VybeHandlingFeesSetting;

/**
 * Class TipSeeder
 *
 * @package Database\Seeders\Faker
 */
class TipSeeder extends Seeder
{
    /**
     * @var string|null
     */
    protected ?string $amount;

    /**
     * Quantity of seeded tip invoices
     */
    protected const TIP_PER_ITEM_QUANTITY = [
        'min' => 1,
        'max' => 3
    ];

    /**
     * @param string|null $amount
     */
    public function __construct(
        ?string $amount = null
    )
    {
        /** @var string amount */
        $this->amount = $amount;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     *
     * @throws DatabaseException
     * @throws GuzzleException
     */
    public function run() : void
    {
        /** @var LogService $logService */
        $logService = app(LogService::class);

        /** @var TipRepository $tipRepository */
        $tipRepository = app(TipRepository::class);

        /** @var TipService $tipService */
        $tipService = app(TipService::class);

        /** @var TipInvoiceRepository $tipInvoiceRepository */
        $tipInvoiceRepository = app(TipInvoiceRepository::class);

        /** @var TipTransactionRepository $tipTransactionRepository */
        $tipTransactionRepository = app(TipTransactionRepository::class);

        /** @var UserHandlingFeesSetting $userHandlingFeesSetting */
        $userHandlingFeesSetting = app(UserHandlingFeesSetting::class);

        /** @var VybeHandlingFeesSetting $vybeHandlingFeesSetting */
        $vybeHandlingFeesSetting = app(VybeHandlingFeesSetting::class);

        /** @var Collection $orderItems */
        $orderItems = app(OrderItemRepository::class)->getAll();

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {

            if ($orderItem->getStatus()->isFinished()) {

                for ($i = 0; $i < self::TIP_PER_ITEM_QUANTITY[$this->amount ?: 'max']; $i++) {

                    /** @var PaymentMethod $paymentMethod */
                    $paymentMethod = PaymentMethod::all()
                        ->random(1)
                        ->first();

                    /** @var User $buyer */
                    $buyer = User::all()
                        ->random(1)
                        ->first();

                    $amount = rand(10, 50);

                    /**
                     * Setting vybe settings
                     */
                    $vybeHandlingFeesSetting->setVybe(
                        $orderItem->vybe
                    );

                    /**
                     * Getting vybe setting tip handling fee
                     */
                    $tipHandlingFee = $vybeHandlingFeesSetting->getVybeTippingHandlingFee();

                    if (!$tipHandlingFee) {

                        /**
                         * Setting user settings
                         */
                        $userHandlingFeesSetting->setUser(
                            $orderItem->seller
                        );

                        /**
                         * Getting user setting tip handling fee
                         */
                        $tipHandlingFee = $userHandlingFeesSetting->getTippingHandlingFee();
                    }

                    $handlingFee = round($amount * ($tipHandlingFee / 100), 2);

                    $amountEarned = $amount - $handlingFee;
                    $amountTax = $tipService->getAmountTax(
                        $buyer->billing,
                        $amount
                    );

                    $paymentFee = rand(10, 20);
                    $paymentFeeTax = $tipService->getAmountTax(
                        $buyer->billing,
                        $paymentFee
                    );

                    $amountTotal = array_sum([
                        $amount,
                        $amountTax,
                        $paymentFee,
                        $paymentFeeTax
                    ]);

                    /**
                     * Creating tip
                     */
                    $tip = $tipRepository->store(
                        $orderItem,
                        $paymentMethod,
                        $buyer,
                        $orderItem->seller,
                        $amount,
                        $amountEarned,
                        $amountTax,
                        $amountTotal,
                        $handlingFee,
                        $paymentFee,
                        $paymentFeeTax
                    );

                    /**
                     * Checking tip existence
                     */
                    if ($tip) {

                        /**
                         * Creating tip buyer invoice
                         */
                        $tipBuyerInvoice = $tipInvoiceRepository->store(
                            $tip,
                            InvoiceTypeList::getTipBuyer(),
                            InvoiceStatusList::getItem(rand(1, 6))
                        );

                        /**
                         * Creating tip seller invoice
                         */
                        $tipSellerInvoice = $tipInvoiceRepository->store(
                            $tip,
                            InvoiceTypeList::getTipSeller(),
                            InvoiceStatusList::getItem(rand(1, 6))
                        );

                        /**
                         * Checking tip buyer existence
                         */
                        if ($tipBuyerInvoice) {

                            /**
                             * Creating tip transaction
                             */
                            $tipTransactionRepository->store(
                                $tip,
                                $paymentMethod,
                                null,
                                $tip->amount_total,
                                rand(10, 20),
                                null
                            );

                            try {

                                /**
                                 * Creating tip invoice for buyer log
                                 */
                                $logService->addTipInvoiceForBuyerLog(
                                    $tipBuyerInvoice,
                                    $tipBuyerInvoice->tip->buyer->getBuyerBalance(),
                                    UserBalanceTypeList::getBuyer(),
                                    'created'
                                );
                            } catch (LogException) {
                                // Ignore exception via faker case uselessness
                            }

                            try {

                                /**
                                 * Creating tip invoice for buyer log
                                 */
                                $logService->addTipInvoiceForBuyerLog(
                                    $tipBuyerInvoice,
                                    $tipBuyerInvoice->tip->buyer->getBuyerBalance(),
                                    UserBalanceTypeList::getBuyer(),
                                    'paid'
                                );
                            } catch (LogException) {
                                // Ignore exception via faker case uselessness
                            }
                        }

                        /**
                         * Checking tip buyer existence
                         */
                        if ($tipSellerInvoice) {

                            try {

                                /**
                                 * Creating tip invoice for seller log
                                 */
                                $logService->addTipInvoiceForSellerLog(
                                    $tipSellerInvoice,
                                    $tipSellerInvoice->tip
                                        ->seller
                                        ->getSellerBalance(),
                                    UserBalanceTypeList::getSeller(),
                                    'created'
                                );
                            } catch (LogException) {
                                // Ignore exception via faker case uselessness
                            }

                            try {

                                /**
                                 * Creating tip invoice for seller log
                                 */
                                $logService->addTipInvoiceForSellerLog(
                                    $tipSellerInvoice,
                                    $tipSellerInvoice->tip
                                        ->seller
                                        ->getSellerBalance(),
                                    UserBalanceTypeList::getSeller(),
                                    'paid'
                                );
                            } catch (LogException) {
                                // Ignore exception via faker case uselessness
                            }
                        }
                    }
                }
            }
        }
    }
}
