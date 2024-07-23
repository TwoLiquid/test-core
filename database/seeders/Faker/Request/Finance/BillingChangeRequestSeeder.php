<?php

namespace Database\Seeders\Faker\Request\Finance;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Models\MySql\Place\CountryPlace;
use App\Models\MySql\User\User;
use App\Repositories\Billing\BillingChangeRequestRepository;
use Illuminate\Database\Seeder;

/**
 * Class BillingChangeRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\Finance
 */
class BillingChangeRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded billing change requests
     */
    protected const BILLING_CHANGE_REQUEST_QUANTITY = 1000;

    /**
     * @var BillingChangeRequestRepository
     */
    protected BillingChangeRequestRepository $billingChangeRequestRepository;

    /**
     * BillingChangeRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var BillingChangeRequestRepository billingChangeRequestRepository */
        $this->billingChangeRequestRepository = new BillingChangeRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::BILLING_CHANGE_REQUEST_QUANTITY; $i++) {

            /** @var User $user */
            $user = User::inRandomOrder()
                ->limit(1)
                ->first();

            /** @var CountryPlace $countryPlace */
            $countryPlace = CountryPlace::inRandomOrder()
                ->limit(1)
                ->first();

            /** @var CountryPlace $previousCountryPlace */
            $previousCountryPlace = CountryPlace::inRandomOrder()
                ->limit(1)
                ->first();

            if ($user) {
                $billingChangeRequest = $this->billingChangeRequestRepository->store(
                    $user,
                    $countryPlace,
                    $previousCountryPlace,
                    null
                );

                if ($billingChangeRequest) {
                    $this->billingChangeRequestRepository->updateRequestStatus(
                        $billingChangeRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->billingChangeRequestRepository->updateLanguage(
                        $billingChangeRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
