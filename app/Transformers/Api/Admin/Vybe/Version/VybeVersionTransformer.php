<?php

namespace App\Transformers\Api\Admin\Vybe\Version;

use App\Models\MongoDb\Vybe\VybeVersion;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Collection;

/**
 * Class VybeVersionTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Version
 */
class VybeVersionTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeVersionImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $vybeVersionVideos;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $previousVybeVersionImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $previousVybeVersionVideos;

    /**
     * @var VybeVersion
     */
    protected VybeVersion $vybeVersion;

    /**
     * @var VybeVersion|null
     */
    protected ?VybeVersion $previousVybeVersion;

    /**
     * VybeVersionTransformer constructor
     *
     * @param VybeVersion $vybeVersion
     * @param VybeVersion|null $previousVybeVersion
     * @param EloquentCollection|null $vybeVersionImages
     * @param EloquentCollection|null $vybeVersionVideos
     * @param EloquentCollection|null $previousVybeVersionImages
     * @param EloquentCollection|null $previousVybeVersionVideos
     */
    public function __construct(
        VybeVersion $vybeVersion,
        ?VybeVersion $previousVybeVersion = null,
        ?EloquentCollection $vybeVersionImages = null,
        ?EloquentCollection $vybeVersionVideos = null,
        ?EloquentCollection $previousVybeVersionImages = null,
        ?EloquentCollection $previousVybeVersionVideos = null
    )
    {
        /** @var VybeVersion vybeVersion */
        $this->vybeVersion = $vybeVersion;

        /** @var VybeVersion previousVybeVersion */
        $this->previousVybeVersion = $previousVybeVersion;

        /** @var EloquentCollection vybeImages */
        $this->vybeVersionImages = $vybeVersionImages;

        /** @var EloquentCollection vybeVideos */
        $this->vybeVersionVideos = $vybeVersionVideos;

        /** @var EloquentCollection previousVybeVersionImages */
        $this->previousVybeVersionImages = $previousVybeVersionImages;

        /** @var EloquentCollection previousVybeVersionVideos */
        $this->previousVybeVersionVideos = $previousVybeVersionVideos;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'images',
        'videos',
        'previous_images',
        'previous_videos'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'id'                         => $this->vybeVersion->_id,
            'title'                      => $this->vybeVersion->title,
            'previous_title'             => $this->previousVybeVersion->title ?? null,
            'category'                   => $this->vybeVersion->category,
            'previous_category'          => $this->previousVybeVersion->category ?? null,
            'subcategory'                => $this->vybeVersion->subcategory,
            'previous_subcategory'       => $this->previousVybeVersion->subcategory ?? null,
            'activity'                   => $this->vybeVersion->activity,
            'previous_activity'          => $this->previousVybeVersion->activity ?? null,
            'devices'                    => $this->vybeVersion->devices,
            'previous_devices'           => $this->previousVybeVersion->devices ?? null,
            'vybe_handling_fee'          => $this->vybeVersion->vybe_handling_fee,
            'previous_vybe_handling_fee' => $this->previousVybeVersion->vybe_handling_fee ?? null,
            'period'                     => $this->vybeVersion->period,
            'previous_period'            => $this->previousVybeVersion->period ?? null,
            'user_count'                 => $this->vybeVersion->user_count,
            'previous_user_count'        => $this->previousVybeVersion->user_count ?? null,
            'appearance_cases'           => $this->vybeVersion->appearance_cases,
            'previous_appearance_cases'  => $this->previousVybeVersion->appearance_cases ?? null,
            'schedules'                  => $this->vybeVersion->schedules,
            'previous_schedules'         => $this->previousVybeVersion->schedules ?? null,
            'order_advance'              => $this->vybeVersion->order_advance,
            'previous_order_advance'     => $this->previousVybeVersion->order_advance ?? null,
            'images_ids'                 => $this->vybeVersion->images_ids,
            'previous_images_ids'        => $this->previousVybeVersion->images_ids ?? null,
            'videos_ids'                 => $this->vybeVersion->videos_ids,
            'previous_videos_ids'        => $this->previousVybeVersion->videos_ids ?? null,
            'access'                     => $this->vybeVersion->access,
            'previous_access'            => $this->previousVybeVersion->access ?? null,
            'showcase'                   => $this->vybeVersion->showcase,
            'previous_showcase'          => $this->previousVybeVersion->showcase ?? null,
            'order_accept'               => $this->vybeVersion->order_accept,
            'previous_order_accept'      => $this->previousVybeVersion->order_accept ?? null,
            'age_limit'                  => $this->vybeVersion->age_limit,
            'previous_age_limit'         => $this->previousVybeVersion->age_limit ?? null,
            'status'                     => $this->vybeVersion->status,
            'previous_status'            => $this->previousVybeVersion->status ?? null
        ];
    }

    /**
     * @return Collection|null
     */
    public function includeImages() : ?Collection
    {
        $vybeVersionImages = null;

        if ($this->vybeVersionImages) {
            $vybeVersion = $this->vybeVersion;

            $vybeVersionImages = $this->vybeVersionImages->filter(function ($item) use ($vybeVersion) {
                return !is_null($vybeVersion->images_ids) && in_array($item->id, $vybeVersion->images_ids);
            });
        }

        return $vybeVersionImages ? $this->collection($vybeVersionImages, new VybeImageTransformer) : null;
    }

    /**
     * @return Collection|null
     */
    public function includeVideos() : ?Collection
    {
        $vybeVersionVideos = null;

        if ($this->vybeVersionVideos) {
            $vybeVersion = $this->vybeVersion;

            $vybeVersionVideos = $this->vybeVersionVideos->filter(function ($item) use ($vybeVersion) {
                return !is_null($vybeVersion->videos_ids) && in_array($item->id, $vybeVersion->videos_ids);
            });
        }

        return $vybeVersionVideos ? $this->collection($vybeVersionVideos, new VybeVideoTransformer) : null;
    }

    /**
     * @return Collection|null
     */
    public function includePreviousImages() : ?Collection
    {
        $previousVybeVersionImages = null;

        if ($this->previousVybeVersionImages) {
            $previousVybeVersion = $this->previousVybeVersion;

            if ($previousVybeVersion) {
                $previousVybeVersionImages = $this->previousVybeVersionImages->filter(function ($item) use ($previousVybeVersion) {
                    return !is_null($previousVybeVersion->images_ids) && in_array($item->id, $previousVybeVersion->images_ids);
                });
            }
        }

        return $previousVybeVersionImages ? $this->collection($previousVybeVersionImages, new VybeImageTransformer) : null;
    }

    /**
     * @return Collection|null
     */
    public function includePreviousVideos() : ?Collection
    {
        $previousVybeVersionVideos = null;

        if ($this->previousVybeVersionVideos) {
            $previousVybeVersion = $this->previousVybeVersion;

            if ($previousVybeVersion) {
                $previousVybeVersionVideos = $this->previousVybeVersionVideos->filter(function ($item) use ($previousVybeVersion) {
                    return !is_null($previousVybeVersion->videos_ids) && in_array($item->id, $previousVybeVersion->videos_ids);
                });
            }
        }

        return $previousVybeVersionVideos ? $this->collection($previousVybeVersionVideos, new VybeVideoTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_version';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_versions';
    }
}
