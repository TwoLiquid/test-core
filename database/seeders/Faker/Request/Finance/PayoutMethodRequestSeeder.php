<?php

namespace Database\Seeders\Faker\Request\Finance;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use App\Repositories\Payout\PayoutMethodRequestRepository;
use Illuminate\Database\Seeder;

/**
 * Class PayoutMethodRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\Finance
 */
class PayoutMethodRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded payout method requests
     */
    protected const PAYOUT_METHOD_REQUEST_QUANTITY = 1000;

    /**
     * @var PayoutMethodRequestRepository
     */
    protected PayoutMethodRequestRepository $payoutMethodRequestRepository;

    /**
     * PayoutMethodRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var PayoutMethodRequestRepository payoutMethodRequestRepository */
        $this->payoutMethodRequestRepository = new PayoutMethodRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::PAYOUT_METHOD_REQUEST_QUANTITY; $i++) {

            /** @var PaymentMethod $paymentMethod */
            $paymentMethod = PaymentMethod::inRandomOrder()
                ->limit(1)
                ->first();

            /** @var User $user */
            $user = User::inRandomOrder()
                ->limit(1)
                ->first();

            $payoutMethodRequest = $this->payoutMethodRequestRepository->store(
                $paymentMethod,
                $user
            );

            if ($payoutMethodRequest) {
                $this->payoutMethodRequestRepository->updateRequestStatus(
                    $payoutMethodRequest,
                    RequestStatusList::getItem(rand(1, 4))
                );

                $this->payoutMethodRequestRepository->updateLanguage(
                    $payoutMethodRequest,
                    LanguageList::getItem(rand(1, 30))
                );
            }
        }
    }
}
