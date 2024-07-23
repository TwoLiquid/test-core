<?php

namespace App\Transformers\Api\Admin\Csau\Platform;

use App\Models\MySql\Platform;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class PlatformWithPaginationTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Platform
 */
class PlatformWithPaginationTransformer extends BaseTransformer
{
    /**
     * @var int
     */
    protected int $page;

    /**
     * @var int
     */
    protected int $perPage;

    /**
     * @var Collection|null
     */
    protected ?Collection $platformIcons;

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'icon',
        'vybes'
    ];

    /**
     * PlatformWithPaginationTransformer constructor
     *
     * @param Collection|null $platformIcons
     * @param int $page
     * @param int $perPage
     */
    public function __construct(
        Collection $platformIcons = null,
        int $page = 1,
        int $perPage = 18
    )
    {
        /** @var Collection platformIcons */
        $this->platformIcons = $platformIcons;

        /** @var int page */
        $this->page = $page;

        /** @var int perPage */
        $this->perPage = $perPage;
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
     * @return Item|null
     */
    public function includeVybes(Platform $platform) : ?Item
    {
        $vybes = null;

        if ($platform->relationLoaded('vybes')) {
            $vybes = $platform->vybes;
        }

        return $vybes ?
            $this->item(
                $vybes,
                new PaginatedVybeTransformer(
                    $this->page,
                    $this->perPage
                )
            ) : null;
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
