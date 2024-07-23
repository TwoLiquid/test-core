<?php

namespace App\Transformers\Api\General\Vybe\Vybe\AppearanceCase;

use App\Models\MySql\Platform;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class PlatformTransformer
 *
 * @package App\Transformers\Api\General\Vybe\Vybe\AppearanceCase
 */
class PlatformTransformer extends BaseTransformer
{
    /**
     * @var Collection|null
     */
    protected ?Collection $platformIcons;

    /**
     * @param Collection|null $platformIcons
     */
    public function __construct(
        ?Collection $platformIcons = null
    )
    {
        /** @var Collection platformIcons */
        $this->platformIcons = $platformIcons;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'icon'
    ];

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
            'visible_in_video_chat' => $platform->visible_in_video_chat
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
