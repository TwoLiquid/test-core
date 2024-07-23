<?php

namespace App\Transformers\Api\Admin\Vybe\Vybe;

use App\Models\MySql\Vybe\Vybe;
use App\Transformers\Api\Admin\Vybe\Form\VybeFormTransformer;
use App\Transformers\Api\Admin\Vybe\Setting\VybeSettingTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;

/**
 * Class VybeTransformer
 *
 * @package App\Transformers\Api\Admin\Vybe\Vybe
 */
class VybePageTransformer extends BaseTransformer
{
    /**
     * @var Vybe
     */
    protected Vybe $vybe;

    /**
     * @var Collection|null
     */
    protected ?Collection $vybeImages;

    /**
     * @var Collection|null
     */
    protected ?Collection $vybeVideos;

    /**
     * @var Collection|null
     */
    protected ?Collection $platformIcons;

    /**
     * VybePageTransformer constructor
     *
     * @param Vybe $vybe
     * @param Collection|null $vybeImages
     * @param Collection|null $vybeVideos
     * @param Collection|null $platformIcons
     */
    public function __construct(
        Vybe $vybe,
        ?Collection $vybeImages = null,
        ?Collection $vybeVideos = null,
        ?Collection $platformIcons = null
    )
    {
        /** @var Vybe vybe */
        $this->vybe = $vybe;

        /** @var Collection vybeImages */
        $this->vybeImages = $vybeImages;

        /** @var Collection vybeVideos */
        $this->vybeVideos = $vybeVideos;

        /** @var Collection platformIcons */
        $this->platformIcons = $platformIcons;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'vybe',
        'settings'
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
    public function includeForm() : ?Item
    {
        return $this->item([], new VybeFormTransformer(
            $this->vybe,
            null,
            $this->platformIcons
        ));
    }

    /**
     * @return Item|null
     */
    public function includeVybe() : ?Item
    {
        return $this->item($this->vybe, new VybeTransformer(
            $this->vybeImages,
            $this->vybeVideos,
            $this->platformIcons
        ));
    }

    /**
     * @return Item|null
     */
    public function includeSettings() : ?Item
    {
        return $this->item([], new VybeSettingTransformer($this->vybe));
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'vybe_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'vybe_pages';
    }
}
