<?php

namespace Database\Seeders\Faker\Request\User;

use App\Exceptions\DatabaseException;
use App\Lists\Account\Status\AccountStatusList;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Models\MySql\User\User;
use App\Repositories\User\UserDeactivationRequestRepository;
use Faker\Provider\nl_NL\Text;
use Illuminate\Database\Seeder;

/**
 * Class UserDeactivationRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\User
 */
class UserDeactivationRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded user deactivation requests
     */
    protected const USER_DEACTIVATION_REQUEST_QUANTITY = 1000;

    /**
     * @var UserDeactivationRequestRepository
     */
    protected UserDeactivationRequestRepository $userDeactivationRequestRepository;

    /**
     * UserDeactivationRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var UserDeactivationRequestRepository userDeactivationRequestRepository */
        $this->userDeactivationRequestRepository = new UserDeactivationRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::USER_DEACTIVATION_REQUEST_QUANTITY; $i++) {

            /** @var User $user */
            $user = User::inRandomOrder()
                ->limit(1)
                ->first();

            if ($user) {
                $userDeactivationRequest = $this->userDeactivationRequestRepository->store(
                    $user,
                    Text::randomLetter(),
                    AccountStatusList::getItem(rand(1, 5)),
                    $user->getAccountStatus()
                );

                if ($userDeactivationRequest) {
                    $this->userDeactivationRequestRepository->updateRequestStatus(
                        $userDeactivationRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->userDeactivationRequestRepository->updateLanguage(
                        $userDeactivationRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
