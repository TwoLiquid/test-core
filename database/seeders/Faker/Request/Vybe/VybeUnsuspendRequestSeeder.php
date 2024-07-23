<?php

namespace Database\Seeders\Faker\Request\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybeUnsuspendRequestRepository;
use Illuminate\Database\Seeder;

/**
 * Class VybeUnsuspendRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\Vybe
 */
class VybeUnsuspendRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded vybe unsuspend requests
     */
    protected const VYBE_UNSUSPEND_REQUEST_QUANTITY = 1000;

    /**
     * @var VybeUnsuspendRequestRepository
     */
    protected VybeUnsuspendRequestRepository $vybeUnsuspendRequestRepository;

    /**
     * VybeUnsuspendRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var VybeUnsuspendRequestRepository vybeUnsuspendRequestRepository */
        $this->vybeUnsuspendRequestRepository = new VybeUnsuspendRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::VYBE_UNSUSPEND_REQUEST_QUANTITY; $i++) {

            /** @var Vybe $vybe */
            $vybe = Vybe::inRandomOrder()
                ->limit(1)
                ->first();

            if ($vybe) {
                $vybeUnsuspendRequest = $this->vybeUnsuspendRequestRepository->store(
                    $vybe,
                    null,
                    VybeStatusList::getItem(rand(1, 5))
                );

                if ($vybeUnsuspendRequest) {
                    $this->vybeUnsuspendRequestRepository->updateRequestStatus(
                        $vybeUnsuspendRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->vybeUnsuspendRequestRepository->updateLanguage(
                        $vybeUnsuspendRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
