<?php

namespace App\Transformers\Api\Admin\Vybe\Vybe\AppearanceCase;

use App\Models\MySql\AppearanceCase;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class AppearanceCaseContainerTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Vybe\AppearanceCase
 */
class AppearanceCaseContainerTransformer extends BaseTransformer
{
    /**
     * @var Collection
     */
    protected Collection $appearanceCases;

    /**
     * @var Collection|null
     */
    protected ?Collection $platformIcons;

    /**
     * AppearanceCaseContainerTransformer constructor
     *
     * @param Collection $appearanceCases
     * @param Collection|null $platformIcons
     */
    public function __construct(
        Collection $appearanceCases,
        ?Collection $platformIcons = null
    )
    {
        /** @var Collection appearanceCases */
        $this->appearanceCases = $appearanceCases;

        /** @var Collection platformIcons */
        $this->platformIcons = $platformIcons;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'voice_chat',
        'video_chat',
        'real_life'
    ];

    /**
     * @return array
     */
    public function transform() : array
    {
        return [];
    }

    /**
     * @return Item|null
     */
    public function includeVoiceChat() : ?Item
    {
        /** @var AppearanceCase $appearanceCase */
        foreach ($this->appearanceCases as $appearanceCase) {
            if ($appearanceCase->getAppearance()->isVoiceChat()) {
                return $this->item($appearanceCase, new AppearanceCaseTransformer($this->platformIcons));
            }
        }

        return null;
    }

    /**
     * @return Item|null
     */
    public function includeVideoChat() : ?Item
    {
        /** @var AppearanceCase $appearanceCase */
        foreach ($this->appearanceCases as $appearanceCase) {
            if ($appearanceCase->getAppearance()->isVideoChat()) {
                return $this->item($appearanceCase, new AppearanceCaseTransformer($this->platformIcons));
            }
        }

        return null;
    }

    /**
     * @return Item|null
     */
    public function includeRealLife() : ?Item
    {
        /** @var AppearanceCase $appearanceCase */
        foreach ($this->appearanceCases as $appearanceCase) {
            if ($appearanceCase->getAppearance()->isRealLife()) {
                return $this->item($appearanceCase, new AppearanceCaseTransformer($this->platformIcons));
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'appearance_cases';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'appearance_cases';
    }
}
