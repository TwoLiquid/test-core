<?php

namespace App\Transformers\Api\General\Vybe;

use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Transformers\Api\General\Vybe\Form\VybeFormTransformer;
use App\Transformers\Api\General\Vybe\Setting\VybeSettingTransformer;
use App\Transformers\Api\General\Vybe\Vybe\VybeTransformer;
use App\Transformers\BaseTransformer;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\Resource\Item;
use ReflectionException;

/**
 * Class VybePageTransformer
 *
 * @package App\Transformers\Api\General\Vybe
 */
class VybePageTransformer extends BaseTransformer
{
    /**
     * @var Vybe|null
     */
    protected ?Vybe $vybe;

    /**
     * @var User|null
     */
    protected ?User $user;

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
     * @param Vybe|null $vybe
     * @param Collection|null $vybeImages
     * @param Collection|null $vybeVideos
     * @param Collection|null $platformIcons
     * @param User|null $user
     */
    public function __construct(
        ?Vybe $vybe = null,
        ?Collection $vybeImages = null,
        ?Collection $vybeVideos = null,
        ?Collection $platformIcons = null,
        ?User $user = null
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

        /** @var User user */
        $this->user = $user;
    }

    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'form',
        'settings',
        'vybe'
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
        return $this->item([], new VybeFormTransformer($this->vybe, null, $this->platformIcons));
    }

    /**
     * @return Item|null
     */
    public function includeSettings() : ?Item
    {
        return $this->user ? $this->item([],
            new VybeSettingTransformer(
                $this->vybe,
                $this->user
            )
        ) : null;
    }

    /**
     * @return Item|null
     *
     * @throws ReflectionException
     */
    public function includeVybe() : ?Item
    {
        return $this->vybe ? $this->item($this->vybe, new VybeTransformer(
            $this->vybe,
            $this->vybeImages,
            $this->vybeVideos,
            $this->platformIcons
        )) : null;
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
