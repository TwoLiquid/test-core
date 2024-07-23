<?php

namespace Database\Seeders\Faker\Request\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybeChangeRequestRepository;
use Illuminate\Database\Seeder;

/**
 * Class VybeChangeRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\Vybe
 */
class VybeChangeRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded vybe change requests
     */
    protected const VYBE_CHANGE_REQUEST_QUANTITY = 1000;

    /**
     * @var VybeChangeRequestRepository
     */
    protected VybeChangeRequestRepository $vybeChangeRequestRepository;

    /**
     * VybeChangeRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var VybeChangeRequestRepository vybeChangeRequestRepository */
        $this->vybeChangeRequestRepository = new VybeChangeRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::VYBE_CHANGE_REQUEST_QUANTITY; $i++) {

            /** @var Vybe $vybe */
            $vybe = Vybe::inRandomOrder()
                ->limit(1)
                ->first();

            if ($vybe) {
                $vybeChangeRequest = $this->vybeChangeRequestRepository->store(
                    $vybe,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
                );

                if ($vybeChangeRequest) {
                    $this->vybeChangeRequestRepository->updateStatus(
                        $vybeChangeRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->vybeChangeRequestRepository->updateLanguage(
                        $vybeChangeRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
