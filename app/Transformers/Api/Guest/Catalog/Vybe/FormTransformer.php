<?php

namespace App\Transformers\Api\Guest\Catalog\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Lists\PersonalityTrait\PersonalityTraitList;
use App\Models\MySql\Category;
use App\Repositories\Device\DeviceRepository;
use App\Repositories\Place\CityPlaceRepository;
use App\Repositories\Platform\PlatformRepository;
use App\Repositories\Unit\UnitRepository;
use App\Services\Activity\ActivityService;
use App\Services\Unit\UnitService;
use App\Transformers\Api\Guest\Catalog\Vybe\AppearanceCase\UnitTransformer;
use App\Transformers\Api\Guest\Catalog\Vybe\Language\LanguageListItemTransformer;
use App\Transformers\Api\Guest\Catalog\Vybe\PersonalityTrait\PersonalityTraitListItemTransformer;
use App\Transformers\BaseTransformer;
use App\Services\Category\CategoryService;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class FormTransformer
 *
 * @package App\Transformers\Api\Guest\Catalog\Vybe
 */
class FormTransformer extends BaseTransformer
{
    /**
     * @var ActivityService
     */
    protected ActivityService $activityService;

    /**
     * @var CategoryService
     */
    protected CategoryService $categoryService;

    /**
     * @var CityPlaceRepository
     */
    protected CityPlaceRepository $cityPlaceRepository;

    /**
     * @var DeviceRepository
     */
    protected DeviceRepository $deviceRepository;

    /**
     * @var PlatformRepository
     */
    protected PlatformRepository $platformRepository;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * @var UnitService
     */
    protected UnitService $unitService;

    /**
     * @var Category|null
     */
    protected ?Category $category = null;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $platformIcons;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'platforms',
        'devices',
        'languages',
        'personality_traits',
        'units',
        'city_places'
    ];

    /**
     * FormTransformer constructor
     *
     * @param Category|null $category
     * @param EloquentCollection|null $platformIcons
     */
    public function __construct(
        ?Category $category = null,
        EloquentCollection $platformIcons = null
    )
    {
        /** @var ActivityService activityService */
        $this->activityService = new ActivityService();

        /** @var CategoryService categoryService */
        $this->categoryService = new CategoryService();

        /** @var CityPlaceRepository cityPlaceRepository */
        $this->cityPlaceRepository = new CityPlaceRepository();

        /** @var DeviceRepository deviceRepository */
        $this->deviceRepository = new DeviceRepository();

        /** @var PlatformRepository platformRepository */
        $this->platformRepository = new PlatformRepository();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();

        /** @var UnitService unitService */
        $this->unitService = new UnitService();

        /** @var Category|null category */
        $this->category = $category;

        /** @var EloquentCollection platformIcons */
        $this->platformIcons = $platformIcons;
    }

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includePlatforms() : ?Collection
    {
        $platforms = $this->platformRepository->getAll();

        return $this->collection(
            $platforms,
            new PlatformTransformer(
                $this->platformIcons
            )
        );
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeDevices() : ?Collection
    {
        $devices = $this->deviceRepository->getAll();

        return $this->collection($devices, new DeviceTransformer);
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeCityPlaces() : ?Collection
    {
        $cityPlaces = $this->cityPlaceRepository->getAllFromVybes();

        return $this->collection($cityPlaces, new CityPlaceTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeLanguages() : ?Collection
    {
        $languages = LanguageList::getItems();

        return $this->collection($languages, new LanguageListItemTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includePersonalityTraits() : ?Collection
    {
        $personalityTraits = PersonalityTraitList::getItems();

        return $this->collection($personalityTraits, new PersonalityTraitListItemTransformer);
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeUnits() : ?Collection
    {
        $units = null;

        if ($this->category) {
            $units = $this->unitService->getByCategory(
                $this->category
            );
        }

        return $units ? $this->collection($units, new UnitTransformer) : null;
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
