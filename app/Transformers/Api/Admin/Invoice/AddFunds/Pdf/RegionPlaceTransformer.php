<?php

namespace App\Transformers\Api\Admin\Invoice\AddFunds\Pdf;

use App\Models\MySql\Place\RegionPlace;
use App\Transformers\BaseTransformer;

/**
 * Class RegionPlaceTransformer
 *
 * @package App\Transformers\Api\Admin\Invoice\AddFunds\Pdf
 */
class RegionPlaceTransformer extends BaseTransformer
{
    /**
     * @param RegionPlace $regionPlace
     *
     * @return array
     */
    public function transform(RegionPlace $regionPlace) : array
    {
        return [
            'id'       => $regionPlace->id,
            'place_id' => $regionPlace->place_id,
            'name'     => $regionPlace->name,
            'code'     => $regionPlace->code
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'region_place';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'region_places';
    }
}
