<?php

namespace Database\Seeders\Faker\Request\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybeUnpublishRequestRepository;
use Illuminate\Database\Seeder;

/**
 * Class VybeUnpublishRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\Vybe
 */
class VybeUnpublishRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded vybe unpublish requests
     */
    protected const VYBE_UNPUBLISH_REQUEST_QUANTITY = 1000;

    /**
     * @var VybeUnpublishRequestRepository
     */
    protected VybeUnpublishRequestRepository $vybeUnpublishRequestRepository;

    /**
     * VybeUnpublishRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var VybeUnpublishRequestRepository vybeUnpublishRequestRepository */
        $this->vybeUnpublishRequestRepository = new VybeUnpublishRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::VYBE_UNPUBLISH_REQUEST_QUANTITY; $i++) {

            /** @var Vybe $vybe */
            $vybe = Vybe::inRandomOrder()
                ->limit(1)
                ->first();

            if ($vybe) {
                $vybeUnpublishRequest = $this->vybeUnpublishRequestRepository->store(
                    $vybe,
                    null,
                    VybeStatusList::getItem(rand(1, 5))
                );

                if ($vybeUnpublishRequest) {
                    $this->vybeUnpublishRequestRepository->updateRequestStatus(
                        $vybeUnpublishRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->vybeUnpublishRequestRepository->updateLanguage(
                        $vybeUnpublishRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
