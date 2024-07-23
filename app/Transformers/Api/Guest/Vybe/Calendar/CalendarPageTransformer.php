<?php

namespace App\Transformers\Api\Guest\Vybe\Calendar;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\UserAvatarRepository;
use App\Services\Activity\ActivityService;
use App\Services\User\UserService;
use App\Transformers\Api\Guest\Vybe\Calendar\Vybe\VybeTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class CalendarPageTransformer
 *
 * @package App\Transformers\Api\Guest\Vybe\Calendar
 */
class CalendarPageTransformer extends BaseTransformer
{
    /**
     * @var Vybe
     */
    protected Vybe $vybe;

    /**
     * @var array
     */
    protected array $slots;

    /**
     * @var ActivityImageRepository
     */
    protected ActivityImageRepository $activityImageRepository;

    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;

    /**
     * @var UserAvatarRepository
     */
    protected UserAvatarRepository $userAvatarRepository;

    /**
     * @var UserService
     */
    protected UserService $userService;

    /**
     * @var Collection|null
     */
    protected ?Collection $usersAvatars;

    /**
     * @var Collection|null
     */
    protected ?Collection $platformsIcons;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'vybe',
        'slots'
    ];

    /**
     * CalendarPageTransformer constructor
     *
     * @param Vybe $vybe
     * @param array $slots
     * @param Collection|null $userAvatars
     * @param Collection|null $platformIcons
     */
    public function __construct(
        Vybe $vybe,
        array $slots,
        ?Collection $userAvatars = null,
        ?Collection $platformIcons = null
    )
    {
        /** @var Vybe vybe */
        $this->vybe = $vybe;

        /** @var array slots */
        $this->slots = $slots;

        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var UserAvatarRepository userAvatarRepository */
        $this->userAvatarRepository = new UserAvatarRepository();

        /** @var UserService userService */
        $this->userService = new UserService();

        /** @var Collection usersAvatars */
        $this->usersAvatars = $userAvatars;

        /** @var Collection platformsIcons */
        $this->platformsIcons = $platformIcons;
    }

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item
     *
     * @throws DatabaseException
     */
    public function includeVybe() : Item
    {
        return $this->item(
            $this->vybe,
            new VybeTransformer(
                $this->activityImageRepository->getByActivities(
                    $this->activityService->getByVybes(
                        new Collection([$this->vybe])
                    )
                ),
                $this->usersAvatars,
                $this->platformsIcons
            )
        );
    }

    /**
     * @return Item
     */
    public function includeSlots() : Item
    {
        return $this->item(
            $this->userService->updateSlotsWithAvatars(
                $this->usersAvatars,
                $this->slots
            ), new SlotsTransformer()
        );
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_calendar_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_calendar_pages';
    }
}
