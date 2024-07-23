<?php

namespace App\Transformers\Api\Admin\User\Vybe;

use App\Exceptions\DatabaseException;
use App\Lists\Vybe\Status\VybeStatusList;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Vybe\VybeSettingRepository;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\Admin\User\Vybe
 */
class VybeTransformer extends BaseTransformer
{
    /**
     * @var VybeSettingRepository
     */
    protected VybeSettingRepository $vybeSettingRepository;

    /**
     * VybeTransformer constructor
     */
    public function __construct()
    {
        /** @var VybeSettingRepository vybeSettingRepository */
        $this->vybeSettingRepository = new VybeSettingRepository();
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'category',
        'subcategory',
        'activity',
        'type',
        'status'
    ];

    /**
     * @param Vybe $vybe
     *
     * @return array
     *
     * @throws DatabaseException
     */
    public function transform(Vybe $vybe) : array
    {
        return [
            'id'                      => $vybe->id,
            'version'                 => $vybe->version,
            'title'                   => $vybe->title,
            'has_custom_handling_fee' => $this->vybeSettingRepository->existsForVybeByCodeCustom(
                $vybe,
                'handling_fees',
                'vybe_handling_fee'
            )
        ];
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeCategory(Vybe $vybe) : ?Item
    {
        $category = $vybe->category;

        if (!$category) {
            if ($vybe->support) {
                $category = $vybe->support->category;
            }
        }

        return $category ? $this->item($category, new CategoryTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeSubcategory(Vybe $vybe) : ?Item
    {
        $subcategory = $vybe->subcategory;

        if (!$subcategory) {
            if ($vybe->support) {
                $subcategory = $vybe->support->subcategory;
            }
        }

        return $subcategory ? $this->item($subcategory, new CategoryTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeActivity(Vybe $vybe) : ?Item
    {
        $activity = null;

        if ($vybe->relationLoaded('activity')) {
            $activity = $vybe->activity;
        }

        return $activity ? $this->item($activity, new ActivityTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeType(Vybe $vybe) : ?Item
    {
        $type = $vybe->getType();

        return $type ? $this->item($type, new VybeTypeTransformer) : null;
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeStatus(Vybe $vybe) : ?Item
    {
        $status = VybeStatusList::getItem(
            $vybe->status_id
        );

        return $status ? $this->item($status, new VybeStatusTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybes';
    }
}
