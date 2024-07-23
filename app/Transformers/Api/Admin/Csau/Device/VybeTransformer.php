<?php

namespace App\Transformers\Api\Admin\Csau\Device;

use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Vybe\Vybe;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Device
 */
class VybeTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'appearances',
        'status'
    ];

    /**
     * @param Vybe $vybe
     *
     * @return array
     */
    public function transform(Vybe $vybe) : array
    {
        return [
            'id'            => $vybe->id,
            'title'         => $vybe->title,
            'version'       => $vybe->version,
            'updated_at'    => $vybe->updated_at->format('Y-m-d\TH:i:s.v\Z'),
            'updated_time'  => $vybe->updated_at->format('H:i'),
            'updated_date'  => $vybe->updated_at->format('d-m-Y')
        ];
    }

    /**
     * @param Vybe $vybe
     *
     * @return Collection|null
     */
    public function includeAppearances(Vybe $vybe) : ?Collection
    {
        $appearances = new EloquentCollection();

        if ($vybe->relationLoaded('appearanceCases')) {
            $appearanceCases = $vybe->appearanceCases;

            /** @var AppearanceCase $appearanceCase */
            foreach ($appearanceCases as $appearanceCase) {
                $appearances->push(
                    $appearanceCase->getAppearance()
                );
            }
        }

        return $this->collection($appearances, new VybeAppearanceTransformer);
    }

    /**
     * @param Vybe $vybe
     *
     * @return Item|null
     */
    public function includeStatus(Vybe $vybe) : ?Item
    {
        $status = $vybe->getStatus();

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
