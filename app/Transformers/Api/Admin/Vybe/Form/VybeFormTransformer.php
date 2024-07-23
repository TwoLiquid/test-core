<?php

namespace App\Transformers\Api\Admin\Vybe\Form;

use App\Exceptions\DatabaseException;
use App\Lists\Vybe\Access\VybeAccessList;
use App\Lists\Vybe\AgeLimit\VybeAgeLimitList;
use App\Lists\Vybe\Appearance\VybeAppearanceList;
use App\Lists\Vybe\OrderAccept\VybeOrderAcceptList;
use App\Lists\Vybe\Period\VybePeriodList;
use App\Lists\Vybe\Showcase\VybeShowcaseList;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Activity\ActivityRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Media\ActivityImageRepository;
use App\Repositories\Media\CategoryIconRepository;
use App\Repositories\Media\DeviceIconRepository;
use App\Repositories\Media\PlatformIconRepository;
use App\Repositories\Place\CountryPlaceRepository;
use App\Repositories\Platform\PlatformRepository;
use App\Repositories\Unit\UnitRepository;
use App\Transformers\Api\Admin\Vybe\Setting\VybeSettingTransformer;
use App\Transformers\Api\Admin\Vybe\Vybe\ActivityTransformer;
use App\Transformers\Api\Admin\Vybe\Vybe\VybeAgeLimitTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class VybeFormTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Form
 */
class VybeFormTransformer extends BaseTransformer
{
    /**
     * @var Vybe|null
     */
    protected ?Vybe $vybe;

    /**
     * @var User|null
     */
    protected ?User $user;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $platformIcons;

    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var ActivityImageRepository
     */
    protected ActivityImageRepository $activityImageRepository;

    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var CategoryIconRepository
     */
    protected CategoryIconRepository $categoryIconRepository;

    /**
     * @var CountryPlaceRepository
     */
    protected CountryPlaceRepository $countryPlaceRepository;

    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * @var DeviceIconRepository
     */
    protected DeviceIconRepository $deviceIconRepository;

    /**
     * @var PlatformRepository
     */
    protected PlatformRepository $platformRepository;

    /**
     * @var PlatformIconRepository
     */
    protected PlatformIconRepository $platformIconRepository;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * VybeFormTransformer constructor
     *
     * @param Vybe|null $vybe
     * @param User|null $user
     * @param EloquentCollection|null $platformIcons
     */
    public function __construct(
        ?Vybe $vybe = null,
        ?User $user = null,
        ?EloquentCollection $platformIcons = null
    )
    {
        /** @var Vybe vybe */
        $this->vybe = $vybe;

        /** @var User user */
        $this->user = $user;

        /** @var EloquentCollection platformIcons */
        $this->platformIcons = $platformIcons;

        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityImageRepository activityImageRepository */
        $this->activityImageRepository = new ActivityImageRepository();

        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var CategoryIconRepository categoryIconRepository */
        $this->categoryIconRepository = new CategoryIconRepository();

        /** @var CountryPlaceRepository countryPlaceRepository */
        $this->countryPlaceRepository = new CountryPlaceRepository();

        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();

        /** @var DeviceIconRepository deviceIconRepository */
        $this->deviceIconRepository = new DeviceIconRepository();

        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();

        /** @var PlatformIconRepository platformIconRepository */
        $this->platformIconRepository = new PlatformIconRepository();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'owner',
        'categories',
        'subcategories',
        'activities',
        'country_places',
        'devices',
        'voice_chat_platforms',
        'video_chat_platforms',
        'periods',
        'appearances',
        'accesses',
        'showcases',
        'order_accepts',
        'statuses',
        'types',
        'units',
        'event_units',
        'age_limits',
        'settings'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item|null
     */
    public function includeOwner() : ?Item
    {
        $user = null;

        if ($this->user) {
            $user = $this->user;
        } elseif ($this->vybe) {
            if ($this->vybe->relationLoaded('user')) {
                $user = $this->vybe->user;
            }
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeCategories() : ?Collection
    {
        $categories = $this->categoryRepository->getParentCategories();

        return $this->collection(
            $categories,
            new CategoryTransformer(
                $this->categoryIconRepository->getByCategories(
                    $categories
                )
            )
        );
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeSubcategories() : ?Collection
    {
        $subcategories = null;

        if ($this->vybe) {
            if ($this->vybe->category) {
                $subcategories = $this->categoryRepository->getByCategory(
                    $this->vybe->category
                );
            }
        }

        return $subcategories ? $this->collection(
            $subcategories,
            new CategoryTransformer(
                $this->categoryIconRepository->getByCategories(
                    $subcategories
                )
            )
        ) : null;
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeActivities() : ?Collection
    {
        $activities = null;

        if ($this->vybe) {
            if ($this->vybe->subcategory) {
                $activities = $this->activityRepository->getByCategory(
                    $this->vybe->subcategory
                );
            } elseif ($this->vybe->category) {
                $activities = $this->activityRepository->getByCategory(
                    $this->vybe->category
                );
            }
        }

        return $activities ? $this->collection(
            $activities,
            new ActivityTransformer(
                $this->activityImageRepository->getByActivities(
                    $activities
                )
            )
        ) : null;
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeCountryPlaces() : ?Collection
    {
        $countryPlaces = $this->countryPlaceRepository->getAll();

        return $this->collection($countryPlaces, new CountryPlaceTransformer);
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeDevices() : ?Collection
    {
        $devices = null;

        if ($this->vybe) {
            if ($this->vybe->relationLoaded('activity') &&
                $this->vybe->activity
            ) {
                $devices = $this->deviceRepository->getByActivity(
                    $this->vybe->activity
                );
            } elseif ($this->vybe->publishRequest) {
                if ($this->vybe->publishRequest->devices_ids) {
                    $devices = $this->deviceRepository->getByIds(
                        $this->vybe->publishRequest->devices_ids
                    );
                }
            } elseif ($this->vybe->changeRequest) {
                if ($this->vybe->changeRequest->devices_ids) {
                    $devices = $this->deviceRepository->getByIds(
                        $this->vybe->changeRequest->devices_ids
                    );
                }
            }
        } else {
            $devices = $this->deviceRepository->getAll();
        }

        return $devices ? $this->collection(
            $devices,
            new DeviceTransformer(
                $this->deviceIconRepository->getByDevices(
                    $devices
                )
            )
        ) : null;
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeVoiceChatPlatforms() : ?Collection
    {
        $platforms = null;

        if ($this->vybe) {
            if ($this->vybe->relationLoaded('activity') &&
                $this->vybe->activity
            ) {
                $platforms = $this->vybe
                    ->activity
                    ->platforms;
            }
        } else {
            $platforms = $this->platformRepository->getAllForVoiceChat();
        }

        return $platforms ? $this->collection(
            $platforms,
            new PlatformTransformer(
                $this->platformIcons
            )
        ) : null;
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeVideoChatPlatforms() : ?Collection
    {
        $platforms = null;

        if ($this->vybe) {
            if ($this->vybe->relationLoaded('activity') &&
                $this->vybe->activity
            ) {
                $platforms = $this->vybe
                    ->activity
                    ->platforms;
            }
        } else {
            $platforms = $this->platformRepository->getAllForVideoChat();
        }

        return $platforms ? $this->collection(
            $platforms,
            new PlatformTransformer(
                $this->platformIcons
            )
        ) : null;
    }

    /**
     * @return Collection|null
     */
    public function includePeriods() : ?Collection
    {
        $periods = VybePeriodList::getItems();

        return $this->collection($periods, new VybePeriodTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeAppearances() : ?Collection
    {
        $periods = VybeAppearanceList::getItems();

        return $this->collection($periods, new VybeAppearanceTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeAccesses() : ?Collection
    {
        $accesses = VybeAccessList::getItems();

        return $this->collection($accesses, new VybeAccessTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeShowcases() : ?Collection
    {
        $showcases = VybeShowcaseList::getItems();

        return $this->collection($showcases, new VybeShowcaseTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeOrderAccepts() : ?Collection
    {
        $orderAccepts = VybeOrderAcceptList::getItems();

        return $this->collection($orderAccepts, new VybeOrderAcceptTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeStatuses() : ?Collection
    {
        if ($this->vybe) {
            $statuses = VybeStatusList::getAdminItemsForStatus(
                $this->vybe->getStatus()
            );
        } else {
            $statuses = VybeStatusList::getItems();
        }

        return $this->collection($statuses, new VybeStatusTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeTypes() : ?Collection
    {
        $types = VybeTypeList::getItems();

        return $this->collection($types, new VybeTypeTransformer);
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeUnits() : ?Collection
    {
        $units = null;

        if ($this->vybe) {
            if ($this->vybe->getType() &&
                !$this->vybe->getType()->isEvent()
            ) {
                if ($this->vybe->activity) {
                    $units = $this->unitRepository->getByActivity(
                        $this->vybe->activity
                    );
                }
            }
        }

        return $units ? $this->collection($units, new UnitTransformer) : null;
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeEventUnits() : ?Collection
    {
        $eventUnits = $this->unitRepository->getAllEvent();

        return $this->collection($eventUnits, new UnitTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeAgeLimits() : ?Collection
    {
        $ageLimits = VybeAgeLimitList::getItems();

        return $this->collection($ageLimits, new VybeAgeLimitTransformer);
    }

    /**
     * @return Item|null
     */
    public function includeSettings() : ?Item
    {
        return $this->item([],
            new VybeSettingTransformer(
                $this->vybe,
                $this->user
            )
        );
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'forms';
    }
}
