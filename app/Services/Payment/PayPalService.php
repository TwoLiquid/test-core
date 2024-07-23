<?php

namespace App\Services\Payment;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Lists\Currency\CurrencyList;
use App\Lists\Currency\CurrencyListItem;
use App\Lists\Payment\Type\PaymentTypeListItem;
use App\Repositories\Payment\PaymentHashRepository;
use App\Services\Payment\Interfaces\PaymentServiceInterface;
use App\Support\Service\PayPal\TransactionResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Drewdan\Paypal\Services\Orders\Order as PayPalOrder;
use JsonMapper_Exception;
use Exception;

/**
 * Class PayPalService
 *
 * @package App\Services\Payment
 */
class PayPalService extends PaymentService implements PaymentServiceInterface
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var PayPalOrder
     */
    protected PayPalOrder $payPalOrder;

    /**
     * @var PaymentHashRepository
     */
    protected PaymentHashRepository $paymentHashRepository;

    /**
     * PayPalService constructor
     */
    public function __construct()
    {
        /** @var Client client */
        $this->client = new Client([
            'headers' => [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . $this->getCredentials()
            ]
        ]);

        /** @var PayPalOrder payPalOrder */
        $this->payPalOrder = new PayPalOrder();

        /** @var PaymentHashRepository paymentHashRepository */
        $this->paymentHashRepository = new PaymentHashRepository();
    }

    /**
     * @return bool
     *
     * @throws BaseException
     * @throws GuzzleException
     */
    public function canGetAuthToken() : bool
    {
        try {

            /**
             * Getting paypal auth token
             */
            $response = $this->client->request(
                'POST',
                config('paypal.sandbox.auth_token_url'), [
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]);

            /**
             * Getting response data
             */
            $responseData = json_decode(
                $response->getBody()->getContents(),
                true
            );

            return isset(
                $responseData['access_token']
            );
        } catch (Exception $exception) {
            if ($exception->getCode() == 404) {
                throw new BaseException(
                    'Invalid auth token url.',
                    $exception->getMessage(),
                    $exception->getCode()
                );
            } else {
                throw new BaseException(
                    'Getting auth token error.',
                    $exception->getMessage(),
                    $exception->getCode()
                );
            }
        }
    }

    /**
     * @return string
     */
    public function getCredentials() : string
    {
        return base64_encode(
            implode(':', [
                config('paypal.client_id'),
                config('paypal.secret')
            ])
        );
    }

    /**
     * @param PaymentTypeListItem $paymentTypeListItem
     * @param CurrencyListItem $currencyListItem
     * @param int $id
     * @param float $amount
     * @param string $hash
     *
     * @return string
     *
     * @throws BaseException
     */
    public function getPaymentUrl(
        PaymentTypeListItem $paymentTypeListItem,
        CurrencyListItem $currencyListItem,
        int $id,
        float $amount,
        string $hash
    ) : string
    {
        /**
         * Setting up purchase units
         */
        $purchaseUnits = [
            [
                'amount' => [
                    'currency_code' => strtoupper(CurrencyList::getEur()->code),
                    'value'         => $amount
                ]
            ]
        ];

        /**
         * Setting up application context
         */
        $applicationContext = [
            'brand_name'          => config('app.company_name'),
            'shipping_preference' => 'NO_SHIPPING',
            'user_action'         => 'PAY_NOW',
            'return_url'          => $this->getReturnUrl(
                $paymentTypeListItem,
                $id,
                $hash
            ),
            'cancel_url' => $this->getCancelUrl(
                $paymentTypeListItem,
                $id,
                $hash
            )
        ];

        try {

            /**
             * Creating order
             */
            $paypalOrder = $this->payPalOrder->create(
                $purchaseUnits,
                'CAPTURE',
                $applicationContext
            );

            /**
             * Creating order payment hash
             */
            $this->paymentHashRepository->store(
                $paypalOrder->id,
                getPaymentHash(
                    $paymentTypeListItem,
                    $id,
                    $hash
                )
            );

            return $paypalOrder->getLinkByRel('approve')
                ->href;
        } catch (Exception $exception) {
            throw new BaseException(
                trans('exceptions/service/payment/payPal.' . __FUNCTION__ . '.payment'),
                $exception->getMessage(),
                $exception->getCode()
            );
        }
    }

    /**
     * @param PaymentTypeListItem $paymentTypeListItem
     * @param int $id
     * @param string $hash
     *
     * @return Collection
     *
     * @throws BaseException
     * @throws DatabaseException
     * @throws JsonMapper_Exception
     */
    public function executePayment(
        PaymentTypeListItem $paymentTypeListItem,
        int $id,
        string $hash
    ) : Collection
    {
        /**
         * Getting payment hash
         */
        $paymentHash = $this->paymentHashRepository->findByHash(
            getPaymentHash(
                $paymentTypeListItem,
                $id,
                $hash
            )
        );

        /**
         * Checking payment hash existence
         */
        if (!$paymentHash) {
            throw new BaseException(
                trans('exceptions/service/payment/payPal.' . __FUNCTION__ . '.payment.hash'),
                null,
                422
            );
        }

        /**
         * Getting PayPal order
         */
        $payPalOrder = $this->payPalOrder->show(
            $paymentHash->external_id
        );

        /**
         * Capturing PayPal order
         */
        $orderResponse = $this->payPalOrder->capture(
            $payPalOrder
        );

        /**
         * Checking PayPal order status
         */
        if ($orderResponse->status != 'COMPLETED') {
            throw new BaseException(
                trans('exceptions/service/payment/payPal.' . __FUNCTION__ . '.order.status'),
                null,
                422
            );
        }

        /**
         * Preparing transaction collections
         */
        $transactions = new Collection();

        foreach ($orderResponse->paymentSource as $transaction) {
            foreach ($transaction->payments->captures as $capture) {

                /**
                 * Adding transaction to a response collection
                 */
                $transactions->push(
                    new TransactionResponse(
                        $capture->id,
                        $capture->seller_receivable_breakdown
                            ->paypal_fee
                            ->value,
                        $capture->seller_receivable_breakdown
                            ->gross_amount
                            ->value
                    )
                );
            }
        }

        return $transactions;
    }
}
