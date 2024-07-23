<?php

namespace Database\Seeders\Faker\Request\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybeDeletionRequestRepository;
use Illuminate\Database\Seeder;

/**
 * Class VybeDeletionRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\Vybe
 */
class VybeDeletionRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded vybe deletion requests
     */
    protected const VYBE_DELETION_REQUEST_QUANTITY = 1000;

    /**
     * @var VybeDeletionRequestRepository
     */
    protected VybeDeletionRequestRepository $vybeDeletionRequestRepository;

    /**
     * VybeDeletionRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var VybeDeletionRequestRepository vybeDeletionRequestRepository */
        $this->vybeDeletionRequestRepository = new VybeDeletionRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::VYBE_DELETION_REQUEST_QUANTITY; $i++) {

            /** @var Vybe $vybe */
            $vybe = Vybe::inRandomOrder()
                ->limit(1)
                ->first();

            if ($vybe) {
                $vybeDeletionRequest = $this->vybeDeletionRequestRepository->store(
                    $vybe,
                    null,
                    VybeStatusList::getItem(rand(1, 5))
                );

                if ($vybeDeletionRequest) {
                    $this->vybeDeletionRequestRepository->updateRequestStatus(
                        $vybeDeletionRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->vybeDeletionRequestRepository->updateLanguage(
                        $vybeDeletionRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
