<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class PlatformIconCollectionResponse
 *
 * @property Collection $icons
 *
 * @package App\Microservices\Media\Responses
 */
class PlatformIconCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $icons;

    /**
     * PlatformIconCollectionResponse constructor
     *
     * @param array $icons
     * @param string|null $message
     */
    public function __construct(
        array $icons,
        ?string $message
    )
    {
        $this->icons = new Collection();

        /** @var object $icon */
        foreach ($icons as $icon) {
            $this->icons->push(
                new PlatformIconResponse(
                    $icon,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}