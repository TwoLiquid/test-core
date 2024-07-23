<?php

namespace App\Transformers\Api\Admin\Vybe\PublishRequest;

use App\Models\MySql\AppearanceCase;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class VybePublishRequestAppearanceCaseContainerTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\PublishRequest
 */
class VybePublishRequestAppearanceCaseContainerTransformer extends BaseTransformer
{
    /**
     * @var Collection
     */
    protected Collection $appearanceCases;

    /**
     * AppearanceCaseContainerTransformer constructor
     *
     * @param Collection $appearanceCases
     */
    public function __construct(
        Collection $appearanceCases
    )
    {
        /** @var Collection appearanceCases */
        $this->appearanceCases = $appearanceCases;
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
                return $this->item($appearanceCase, new VybePublishRequestAppearanceCaseTransformer);
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
                return $this->item($appearanceCase, new VybePublishRequestAppearanceCaseTransformer);
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
                return $this->item($appearanceCase, new VybePublishRequestAppearanceCaseTransformer);
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
