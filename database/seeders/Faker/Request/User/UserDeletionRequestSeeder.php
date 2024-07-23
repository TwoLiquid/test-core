<?php

namespace Database\Seeders\Faker\Request\User;

use App\Exceptions\DatabaseException;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Models\MySql\User\User;
use App\Repositories\User\UserDeletionRequestRepository;
use Faker\Provider\nl_NL\Text;
use Illuminate\Database\Seeder;

/**
 * Class UserDeletionRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\User
 */
class UserDeletionRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded user unsuspend requests
     */
    protected const USER_DELETION_REQUEST_QUANTITY = 1000;

    /**
     * @var UserDeletionRequestRepository
     */
    protected UserDeletionRequestRepository $userDeletionRequestRepository;

    /**
     * UserDeletionRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var UserDeletionRequestRepository userDeletionRequestRepository */
        $this->userDeletionRequestRepository = new UserDeletionRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::USER_DELETION_REQUEST_QUANTITY; $i++) {

            /** @var User $user */
            $user = User::inRandomOrder()
                ->limit(1)
                ->first();

            if ($user) {
                $userDeletionRequest = $this->userDeletionRequestRepository->store(
                    $user,
                    Text::randomLetter(),
                    AccountStatusList::getItem(rand(1, 5)),
                    $user->getAccountStatus()
                );

                if ($userDeletionRequest) {
                    $this->userDeletionRequestRepository->updateRequestStatus(
                        $userDeletionRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->userDeletionRequestRepository->updateLanguage(
                        $userDeletionRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
