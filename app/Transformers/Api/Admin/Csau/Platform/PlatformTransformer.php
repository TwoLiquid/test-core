<?php

namespace App\Transformers\Api\Admin\Csau\Platform;

use App\Models\MySql\Platform;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use League\Fractal\Resource\Item;

/**
 * Class PlatformTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Platform
 */
class PlatformTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $platformIcons;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'icon',
        'vybes'
    ];

    /**
     * PlatformTransformer constructor
     *
     * @param EloquentCollection|null $platformIcons
     */
    public function __construct(
        EloquentCollection $platformIcons = null
    )
    {
        /** @var EloquentCollection platformIcons */
        $this->platformIcons = $platformIcons;
    }

    /**
     * @param Platform $platform
     *
     * @return array
     */
    public function transform(Platform $platform) : array
    {
        return [
            'id'                    => $platform->id,
            'name'                  => $platform->name,
            'code'                  => $platform->code,
            'voice_chat'            => $platform->voice_chat,
            'visible_in_voice_chat' => $platform->visible_in_voice_chat,
            'video_chat'            => $platform->video_chat,
            'visible_in_video_chat' => $platform->visible_in_video_chat,
            'vybes_count'           => $platform->vybes_count ? $platform->vybes_count : 0
        ];
    }

    /**
     * @param Platform $platform
     *
     * @return Item|null
     */
    public function includeIcon(Platform $platform) : ?Item
    {
        $platformIcon = $this->platformIcons?->filter(function ($item) use ($platform) {
            return $item->platform_id == $platform->id;
        })->first();

        return $platformIcon ? $this->item($platformIcon, new PlatformIconTransformer) : null;
    }

    /**
     * @param Platform $platform
     *
     * @return Collection|null
     */
    public function includeVybes(Platform $platform) : ?Collection
    {
        $vybes = null;

        if ($platform->relationLoaded('vybes')) {
            $vybes = $platform->vybes;
        }

        return $vybes ? $this->collection($vybes, new VybeTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'platform';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'platforms';
    }
}
