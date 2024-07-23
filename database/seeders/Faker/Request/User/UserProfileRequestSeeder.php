<?php

namespace Database\Seeders\Faker\Request\User;

use App\Exceptions\DatabaseException;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Models\MySql\User\User;
use App\Repositories\User\UserProfileRequestRepository;
use Faker\Provider\DateTime;
use Faker\Provider\sr_Latn_RS\Person;
use Illuminate\Database\Seeder;

/**
 * Class UserProfileRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\User
 */
class UserProfileRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded user profile requests
     */
    protected const USER_PROFILE_REQUEST_QUANTITY = 1000;

    /**
     * @var UserProfileRequestRepository
     */
    protected UserProfileRequestRepository $userProfileRequestRepository;

    /**
     * UserProfileRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var UserProfileRequestRepository userProfileRequestRepository */
        $this->userProfileRequestRepository = new UserProfileRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::USER_PROFILE_REQUEST_QUANTITY; $i++) {

            /** @var User $user */
            $user = User::inRandomOrder()
                ->limit(1)
                ->first();

            if ($user) {
                $userProfileRequest = $this->userProfileRequestRepository->store(
                    $user,
                    AccountStatusList::getActive(),
                    null,
                    Person::firstNameMale(),
                    null,
                    DateTime::iso8601(),
                    null,
                    null,
                    null
                );

                if ($userProfileRequest) {
                    $this->userProfileRequestRepository->updateRequestStatus(
                        $userProfileRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->userProfileRequestRepository->updateLanguage(
                        $userProfileRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
