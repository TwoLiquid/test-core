<?php

namespace App\Transformers\Api\Admin\Vybe\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Lists\Vybe\Type\VybeTypeList;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Unit\UnitRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;

/**
 * Class VybeFilterFormTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Vybe
 */
class VybeFilterFormTransformer extends BaseTransformer
{
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var UnitRepository
     */
    protected UnitRepository $unitRepository;

    /**
     * VybeFilterFormTransformer constructor
     */
    public function __construct()
    {
        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var UnitRepository unitRepository */
        $this->unitRepository = new UnitRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'categories',
        'units',
        'types',
        'statuses'
    ];

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
    public function includeCategories() : ?Collection
    {
        $categories = $this->categoryRepository->getAll();

        return $this->collection($categories, new CategoryTransformer);
    }

    /**
     * @return Collection|null
     *
     * @throws DatabaseException
     */
    public function includeUnits() : ?Collection
    {
        $unit = $this->unitRepository->getAll();

        return $this->collection($unit, new UnitTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeTypes() : ?Collection
    {
        $vybeTypes = VybeTypeList::getItems();

        return $this->collection($vybeTypes, new VybeTypeTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeStatuses() : ?Collection
    {
        $vybeStatuses = VybeStatusList::getItems();

        return $this->collection($vybeStatuses, new VybeStatusTransformer);
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
