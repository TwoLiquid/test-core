<?php

namespace App\Transformers\Api\Guest\Navbar;

use App\Repositories\Category\CategoryRepository;
use App\Repositories\Timezone\TimezoneRepository;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class NavbarTransformer
 *
 * @package App\Transformers\Api\Guest\Navbar
 */
class NavbarTransformer extends BaseTransformer
{
    /**
     * @var CategoryRepository
     */
    protected CategoryRepository $categoryRepository;

    /**
     * @var TimezoneRepository
     */
    protected TimezoneRepository $timezoneRepository;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $categoryIcons;

    /**
     * NavbarTransformer constructor
     *
     * @param EloquentCollection|null $categoryIcons
     */
    public function __construct(
        EloquentCollection $categoryIcons = null
    )
    {
        /** @var CategoryRepository categoryRepository */
        $this->categoryRepository = new CategoryRepository();

        /** @var TimezoneRepository timezoneRepository */
        $this->timezoneRepository = new TimezoneRepository();

        /** @var EloquentCollection categoryIcons */
        $this->categoryIcons = $categoryIcons;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item
     */
    public function includeForm() : Item
    {
        return $this->item([], new FormTransformer);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'navbar';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'navbars';
    }
}
