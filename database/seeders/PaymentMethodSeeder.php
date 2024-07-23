<?php

namespace Database\Seeders;

use App\Exceptions\DatabaseException;
use App\Lists\Payment\Method\Payment\Status\PaymentStatusList;
use App\Lists\Payment\Method\Withdrawal\Status\PaymentMethodWithdrawalStatusList;
use App\Repositories\Payment\PaymentMethodFieldRepository;
use App\Repositories\Payment\PaymentMethodRepository;
use Illuminate\Database\Seeder;

/**
 * Class PaymentMethodSeeder
 *
 * @package Database\Seeders
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
                'name'            => 'Balance',
                'payment_fee'     => 0,
                'order_form'      => true,
                'display_name'    => ['en' => 'Balance'],
                'duration_title'  => ['en' => 'Duration'],
                'duration_amount' => ['en' => 'Instant'],
                'fee_title'       => ['en' => 'Fee'],
                'fee_amount'      => ['en' => 'None'],
                'fields'          => [],
                'standard'        => true
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
