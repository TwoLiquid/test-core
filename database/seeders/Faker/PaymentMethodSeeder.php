<?php

namespace Database\Seeders\Faker;

use App\Exceptions\DatabaseException;
use App\Lists\Payment\Method\Field\Type\PaymentMethodFieldTypeList;
use App\Lists\Payment\Method\Payment\Status\PaymentStatusList;
use App\Lists\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusList;
use App\Repositories\Payment\PaymentMethodFieldRepository;
use App\Repositories\Payment\PaymentMethodRepository;
use Illuminate\Database\Seeder;

/**
 * Class PaymentMethodSeeder
 *
 * @package Database\Seeders\Faker
 */
class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        /** @var PaymentMethodRepository $paymentMethodRepository */
        $paymentMethodRepository = app(PaymentMethodRepository::class);

        /** @var PaymentMethodFieldRepository $paymentMethodFieldRepository */
        $paymentMethodFieldRepository = app(PaymentMethodFieldRepository::class);

        $paymentStatus = PaymentStatusList::getActive();
        $paymentMethodWithdrawalStatus = PaymentMethodWithdrawalStatusList::getActive();

        $methods = [
            [
                'name'            => 'PayPal',
                'payment_fee'     => 2.90,
                'order_form'      => true,
                'display_name'    => ['en' => 'PayPal + Credit Card'],
                'duration_title'  => ['en' => 'Duration'],
                'duration_amount' => ['en' => '3-5 business days'],
                'fee_title'       => ['en' => 'Fee'],
                'fee_amount'      => ['en' => '2.90% + fixed fee'],
                'fields'          => [
                    [
                        'type'        => PaymentMethodFieldTypeList::getString(),
                        'title'       => ['en' => 'Account holder name'],
                        'placeholder' => ['en' => 'Enter full name of the PayPal account holder'],
                        'tooltip'     => ['en' => 'Tooltip']
                    ]
                ],
                'standard'        => false
            ],
            [
                'name'            => 'SEPA bank',
                'payment_fee'     => 1.50,
                'order_form'      => false,
                'display_name'    => ['en' => 'SEPA bank + Credit Card'],
                'duration_title'  => ['en' => 'Duration'],
                'duration_amount' => ['en' => '1-2 business days'],
                'fee_title'       => ['en' => 'Fee'],
                'fee_amount'      => ['en' => '1.50% + fixed fee'],
                'fields'          => [
                    [
                        'type'        => PaymentMethodFieldTypeList::getString(),
                        'title'       => ['en' => 'Account holder name'],
                        'placeholder' => ['en' => 'Enter full name of the SEPA bank account holder'],
                        'tooltip'     => ['en' => 'Tooltip']
                    ]
                ],
                'standard'        => false
            ],
            [
                'name'            => 'International bank',
                'payment_fee'     => 2.50,
                'order_form'      => false,
                'display_name'    => ['en' => 'International bank + Credit Card'],
                'duration_title'  => ['en' => 'Duration'],
                'duration_amount' => ['en' => '1-2 business days'],
                'fee_title'       => ['en' => 'Fee'],
                'fee_amount'      => ['en' => '2.50% + fixed fee'],
                'fields'          => [
                    [
                        'type'        => PaymentMethodFieldTypeList::getString(),
                        'title'       => ['en' => 'Account holder name'],
                        'placeholder' => ['en' => 'Enter full name of the International bank account holder'],
                        'tooltip'     => ['en' => 'Tooltip']
                    ]
                ],
                'standard'        => false
            ],
            [
                'name'            => 'Wise',
                'payment_fee'     => 2.50,
                'order_form'      => false,
                'display_name'    => ['en' => 'Wise'],
                'duration_title'  => ['en' => 'Duration'],
                'duration_amount' => ['en' => '1-2 business days'],
                'fee_title'       => ['en' => 'Fee'],
                'fee_amount'      => ['en' => '2.50% + fixed fee'],
                'fields'          => [
                    [
                        'type'        => PaymentMethodFieldTypeList::getString(),
                        'title'       => ['en' => 'Account holder name'],
                        'placeholder' => ['en' => 'Enter full name of the Wise account holder'],
                        'tooltip'     => ['en' => 'Tooltip']
                    ]
                ],
                'standard'        => false
            ],
            [
                'name'            => 'Payoneer',
                'payment_fee'     => 4.50,
                'order_form'      => false,
                'display_name'    => ['en' => 'Payoneer'],
                'duration_title'  => ['en' => 'Duration'],
                'duration_amount' => ['en' => '1-2 business days'],
                'fee_title'       => ['en' => 'Fee'],
                'fee_amount'      => ['en' => '4.50% + fixed fee'],
                'fields'          => [
                    [
                        'type'        => PaymentMethodFieldTypeList::getString(),
                        'title'       => ['en' => 'Account holder name'],
                        'placeholder' => ['en' => 'Enter full name of the Payoneer account holder'],
                        'tooltip'     => ['en' => 'Tooltip']
                    ]
                ],
                'standard'        => false
            ]
        ];

        foreach ($methods as $method) {
            $paymentMethod = $paymentMethodRepository->store(
                $paymentStatus,
                $method['standard'] ?
                    PaymentMethodWithdrawalStatusList::getInactive() :
                    $paymentMethodWithdrawalStatus,
                $method['name'],
                generateCodeByName($method['name']),
                $method['payment_fee'],
                $method['order_form'],
                $method['display_name'],
                $method['duration_title'],
                $method['duration_amount'],
                $method['fee_title'],
                $method['fee_amount'],
                $method['standard']
            );

            if ($paymentMethod) {
                $paymentMethodRepository->updateIntegrated(
                    $paymentMethod,
                    true
                );

                /** @var array $field */
                foreach ($method['fields'] as $field) {
                    $paymentMethodFieldRepository->store(
                        $paymentMethod,
                        $field['type'],
                        $field['title'],
                        $field['placeholder'],
                        $field['tooltip']
                    );
                }
            }
        }
    }
}
