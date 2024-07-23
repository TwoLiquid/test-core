<?php

namespace Database\Seeders\Faker\Request\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\Request\Status\RequestStatusList;
use App\Lists\Vybe\Access\VybeAccessList;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Lists\Vybe\Showcase\VybeShowcaseList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybePublishRequestRepository;
use Faker\Provider\nl_NL\Text;
use Illuminate\Database\Seeder;

/**
 * Class VybePublishRequestSeeder
 *
 * @package Database\Seeders\Faker\Request\Vybe
 */
class VybePublishRequestSeeder extends Seeder
{
    /**
     * Quantity of seeded vybe publish requests
     */
    protected const VYBE_PUBLISH_REQUEST_QUANTITY = 1000;

    /**
     * @var VybePublishRequestRepository
     */
    protected VybePublishRequestRepository $vybePublishRequestRepository;

    /**
     * VybePublishRequestSeeder constructor
     */
    public function __construct()
    {
        /** @var VybePublishRequestRepository vybePublishRequestRepository */
        $this->vybePublishRequestRepository = new VybePublishRequestRepository();
    }

    /**
     * Run the database seeds
     *
     * @throws DatabaseException
     */
    public function run() : void
    {
        for ($i = 0; $i < self::VYBE_PUBLISH_REQUEST_QUANTITY; $i++) {

            /** @var Vybe $vybe */
            $vybe = Vybe::inRandomOrder()
                ->limit(1)
                ->first();

            if ($vybe) {
                $vybePublishRequest = $this->vybePublishRequestRepository->store(
                    $vybe,
                    Text::randomLetter(),
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    VybePeriodList::getItem(rand(1, 2)),
                    rand(1, 10),
                    VybeTypeList::getItem(rand(1, 3)),
                    null,
                    null,
                    null,
                    null,
                    VybeAccessList::getItem(rand(1, 2)),
                    VybeShowcaseList::getItem(rand(1, 2)),
                    null,
                    VybeStatusList::getItem(rand(1, 5))
                );

                if ($vybePublishRequest) {
                    $this->vybePublishRequestRepository->updateRequestStatus(
                        $vybePublishRequest,
                        RequestStatusList::getItem(rand(1, 4))
                    );

                    $this->vybePublishRequestRepository->updateLanguage(
                        $vybePublishRequest,
                        LanguageList::getItem(rand(1, 30))
                    );
                }
            }
        }
    }
}
