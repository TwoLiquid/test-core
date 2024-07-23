<?php

namespace App\Transformers\Api\Admin\General\Admin;

use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class AdminTransformer
 *
 * @package App\Transformers\Api\Admin\General\Admin
 */
class AdminPageTransformer extends BaseTransformer
{
    /**
     * @var int
     */
    protected int $total;

    /**
     * @var EloquentCollection
     */
    protected EloquentCollection $admins;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $adminAvatars;

    /**
     * AdminPageTransformer constructor
     *
     * @param int $total
     * @param EloquentCollection $admins
     * @param EloquentCollection|null $adminAvatars
     */
    public function __construct(
        int $total,
        EloquentCollection $admins,
        EloquentCollection $adminAvatars = null
    )
    {
        /** @var int total */
        $this->total = $total;

        /** @var EloquentCollection admins */
        $this->admins = $admins;

        /** @var EloquentCollection adminAvatars */
        $this->adminAvatars = $adminAvatars;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'admins'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'total' => $this->total
        ];
    }

    /**
     * @return Item
     */
    public function includeForm() : Item
    {
        return $this->item([], new AdminFormTransformer);
    }

    /**
     * @return Collection|null
     */
    public function includeAdmins() : ?Collection
    {
        return $this->collection(
            $this->admins,
            new AdminTransformer(
                $this->adminAvatars
            )
        );
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'admin_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'admin_pages';
    }
}
