<?php

namespace App\Microservices\Media\Responses;

use Illuminate\Database\Eloquent\Collection;

/**
 * Class DeviceIconCollectionResponse
 *
 * @property Collection $icons
 *
 * @package App\Microservices\Media\Responses
 */
class DeviceIconCollectionResponse extends BaseResponse
{
    /**
     * @var Collection
     */
    public Collection $icons;

    /**
     * DeviceIconCollectionResponse constructor
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
                new DeviceIconResponse(
                    $icon,
                    null
                )
            );
        }

        parent::__construct($message);
    }
}