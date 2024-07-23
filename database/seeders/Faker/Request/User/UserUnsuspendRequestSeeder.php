<?php

namespace Database\Seeders\Faker\Request\User;

use App\Exceptions\DatabaseException;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Models\MySql\User\User;
use App\Repositories\User\UserUnsuspendRequestRepository;
use Faker\Provider\nl_NL\Text;
use Illuminate\Database\Seeder;

/**
 * Class UserUnsuspendRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\User
 */
class UserUnsuspendRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded user unsuspend requests
     */
    protected const USER_UNSUSPEND_REQUEST_QUANTITY = 1000;

    /**
     * @var UserUnsuspendRequestRepository
     */
    protected UserUnsuspendRequestRepository $userUnsuspendRequestRepository;

    /**
     * UserUnsuspendRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var UserUnsuspendRequestRepository userUnsuspendRequestRepository */
        $this->userUnsuspendRequestRepository = new UserUnsuspendRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::USER_UNSUSPEND_REQUEST_QUANTITY; $i++) {

            /** @var User $user */
            $user = User::inRandomOrder()
                ->limit(1)
                ->first();

            if ($user) {
                $userUnsuspendRequest = $this->userUnsuspendRequestRepository->store(
                    $user,
                    Text::randomLetter(),
                    AccountStatusList::getItem(rand(1, 5)),
                    $user->getAccountStatus()
                );

                if ($userUnsuspendRequest) {
                    $this->userUnsuspendRequestRepository->updateRequestStatus(
                        $userUnsuspendRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->userUnsuspendRequestRepository->updateLanguage(
                        $userUnsuspendRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
