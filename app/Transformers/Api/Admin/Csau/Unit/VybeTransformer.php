<?php

namespace App\Transformers\Api\Admin\Csau\Unit;

use App\Models\MySql\AppearanceCase;
use App\Models\MySql\Vybe\Vybe;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Unit
 */
class VybeTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'appearances',
        'status',
        'activity',
        'type'
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
