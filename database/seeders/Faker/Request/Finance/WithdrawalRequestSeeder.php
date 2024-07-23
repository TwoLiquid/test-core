<?php

namespace Database\Seeders\Faker\Request\Finance;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Models\MySql\Payment\PaymentMethod;
use App\Models\MySql\User\User;
use App\Repositories\Receipt\WithdrawalRequestRepository;
use Illuminate\Database\Seeder;

/**
 * Class WithdrawalRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\Finance
 */
class WithdrawalRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded withdrawal requests
     */
    protected const WITHDRAWAL_REQUEST_QUANTITY = 1000;

    /**
     * @var WithdrawalRequestRepository
     */
    protected WithdrawalRequestRepository $withdrawalRequestRepository;

    /**
     * WithdrawalRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var WithdrawalRequestRepository withdrawalRequestRepository */
        $this->withdrawalRequestRepository = new WithdrawalRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::WITHDRAWAL_REQUEST_QUANTITY; $i++) {

            /** @var User $user */
            $user = User::inRandomOrder()
                ->limit(1)
                ->first();

            /** @var PaymentMethod $payoutMethod */
            $payoutMethod = PaymentMethod::inRandomOrder()
                ->limit(1)
                ->first();

            if ($user) {
                $withdrawalRequest = $this->withdrawalRequestRepository->store(
                    $user,
                    $payoutMethod,
                    rand(5, 5000)
                );

                if ($withdrawalRequest) {
                    $this->withdrawalRequestRepository->updateRequestStatus(
                        $withdrawalRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->withdrawalRequestRepository->updateLanguage(
                        $withdrawalRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
