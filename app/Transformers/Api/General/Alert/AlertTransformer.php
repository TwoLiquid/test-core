<?php

namespace App\Transformers\Api\General\Alert;

use App\Models\MySql\Alert\Alert;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

/**
 * Class AlertTransformer
 *
 * @package App\Transformers\Api\General\Alert
 */
class AlertTransformer extends BaseTransformer
{
    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $alertImages;

    /**
     * @var EloquentCollection|null
     */
    protected ?EloquentCollection $alertSounds;

    /**
     * AlertTransformer constructor
     *
     * @param EloquentCollection|null $alertImages
     * @param EloquentCollection|null $alertSounds
     */
    public function __construct(
        EloquentCollection $alertImages = null,
        EloquentCollection $alertSounds = null
    )
    {
        /** @var EloquentCollection alertImages */
        $this->alertImages = $alertImages;

        /** @var EloquentCollection alertSounds */
        $this->alertSounds = $alertSounds;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'images',
        'sounds',
        'user',
        'type',
        'animation',
        'align',
        'text_font',
        'text_style',
        'logo_align',
        'profanity_filter'
    ];

    /**
     * @param Alert $alert
     *
     * @return array
     */
    public function transform(Alert $alert) : array
    {
        return [
            'id'              => $alert->id,
            'duration'        => $alert->duration,
            'text_font_color' => $alert->text_font_color,
            'text_font_size'  => $alert->text_font_size,
            'logo_color'      => $alert->logo_color,
            'username'        => $alert->username,
            'amount'          => $alert->amount,
            'action'          => $alert->action,
            'text_message'    => $alert->message,
            'widget_url'      => $alert->widget_url
        ];
    }

    /**
     * @param Alert $alert
     *
     * @return Collection|null
     */
    public function includeImages(Alert $alert) : ?Collection
    {
        $alertImages = $this->alertImages?->filter(function ($item) use ($alert) {
            return $item->alert_id == $alert->id;
        });

        return $alertImages ? $this->collection($alertImages, new AlertImageTransformer) : null;
    }

    /**
     * @param Alert $alert
     *
     * @return Collection|null
     */
    public function includeSounds(Alert $alert) : ?Collection
    {
        $alertSounds = $this->alertSounds?->filter(function ($item) use ($alert) {
            return $item->alert_id == $alert->id;
        });

        return $alertSounds ? $this->collection($alertSounds, new AlertSoundTransformer) : null;
    }

    /**
     * @param Alert $alert
     *
     * @return Item|null
     */
    public function includeUser(Alert $alert) : ?Item
    {
        $user = null;

        if ($alert->relationLoaded('user')) {
            $user = $alert->user;
        }

        return $user ? $this->item($user, new UserTransformer) : null;
    }

    /**
     * @param Alert $alert
     *
     * @return Item|null
     */
    public function includeType(Alert $alert) : ?Item
    {
        $type = $alert->getType();

        return $type ? $this->item($type, new AlertTypeTransformer) : null;
    }

    /**
     * @param Alert $alert
     *
     * @return Item|null
     */
    public function includeAnimation(Alert $alert) : ?Item
    {
        $animation = $alert->getAnimation();

        return $animation ? $this->item($animation, new AlertAnimationTransformer) : null;
    }

    /**
     * @param Alert $alert
     *
     * @return Item|null
     */
    public function includeAlign(Alert $alert) : ?Item
    {
        $align = $alert->getAlign();

        return $align ? $this->item($align, new AlertAlignTransformer) : null;
    }

    /**
     * @param Alert $alert
     *
     * @return Item|null
     */
    public function includeTextFont(Alert $alert) : ?Item
    {
        $textFont = $alert->getTextFont();

        return $textFont ? $this->item($textFont, new AlertTextFontTransformer) : null;
    }

    /**
     * @param Alert $alert
     *
     * @return Item|null
     */
    public function includeTextStyle(Alert $alert) : ?Item
    {
        $textStyle = $alert->getTextStyle();

        return $textStyle ? $this->item($textStyle, new AlertTextStyleTransformer) : null;
    }

    /**
     * @param Alert $alert
     *
     * @return Item|null
     */
    public function includeLogoAlign(Alert $alert) : ?Item
    {
        $logoAlign = $alert->getLogoAlign();

        return $logoAlign ? $this->item($logoAlign, new AlertLogoAlignTransformer) : null;
    }

    /**
     * @param Alert $alert
     *
     * @return Item|null
     */
    public function includeProfanityFilter(Alert $alert) : ?Item
    {
        $profanityFilter = null;

        if ($alert->relationLoaded('filter')) {
            $profanityFilter = $alert->filter;
        }

        return $profanityFilter ? $this->item($profanityFilter, new AlertProfanityFilterTransformer) : null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'alert';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'alerts';
    }
}
