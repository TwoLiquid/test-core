<?php

namespace Database\Seeders\Faker\Request\User;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\User\IdVerification\Status\UserIdVerificationStatusList;
use App\Models\MySql\User\User;
use App\Repositories\User\UserIdVerificationRequestRepository;
use Illuminate\Database\Seeder;

/**
 * Class UserIdVerificationRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\User
 */
class UserIdVerificationRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded user id verification requests
     */
    protected const USER_ID_VERIFICATION_REQUEST_QUANTITY = 1000;

    /**
     * @var UserIdVerificationRequestRepository
     */
    protected UserIdVerificationRequestRepository $userIdVerificationRequestRepository;

    /**
     * UserIdVerificationRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var UserIdVerificationRequestRepository userIdVerificationRequestRepository */
        $this->userIdVerificationRequestRepository = new UserIdVerificationRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::USER_ID_VERIFICATION_REQUEST_QUANTITY; $i++) {

            /** @var User $user */
            $user = User::inRandomOrder()
                ->limit(1)
                ->first();

            if ($user) {
                $userIdVerificationRequest = $this->userIdVerificationRequestRepository->store(
                    $user,
                    UserIdVerificationStatusList::getItem(
                        rand(1, 3)
                    )
                );

                if ($userIdVerificationRequest) {
                    $this->userIdVerificationRequestRepository->updateRequestStatus(
                        $userIdVerificationRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->userIdVerificationRequestRepository->updateLanguage(
                        $userIdVerificationRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
