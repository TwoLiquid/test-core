<?php

namespace App\Transformers\Api\Admin\Csau\Suggestion;

use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Models\MongoDb\Suggestion\DeviceSuggestion;
use App\Transformers\Api\Admin\Csau\Suggestion\Activity\ActivityTransformer;
use App\Transformers\Api\Admin\Csau\Suggestion\Device\DeviceTransformer;
use App\Transformers\Api\Admin\Csau\Suggestion\Vybe\VybeTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class DeviceSuggestionTransformer
 *
 * @package App\Transformers\Api\DeviceSuggestion
 */
class DeviceSuggestionTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'vybe',
        'activity',
        'device',
        'status',
        'admin'
    ];

    /**
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return array
     */
    public function transform(DeviceSuggestion $deviceSuggestion) : array
    {
        return [
            'id'                  => $deviceSuggestion->_id,
            'suggestion'          => $deviceSuggestion->suggestion,
            'activity_suggestion' => $this->getActivitySuggestion($deviceSuggestion),
            'visible'             => $deviceSuggestion->visible,
            'created_at'          => $deviceSuggestion->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return Item|null
     */
    public function includeUser(DeviceSuggestion $deviceSuggestion) : ?Item
    {
        if ($deviceSuggestion->relationLoaded('vybePublishRequest')) {
            $vybePublishRequest = $deviceSuggestion->vybePublishRequest;

            if ($vybePublishRequest) {
                if ($vybePublishRequest->relationLoaded('vybe')) {
                    $vybe = $vybePublishRequest->vybe;

                    if ($vybe->relationLoaded('user')) {
                        $user = $vybe->user;

                        return $user ? $this->item($user, new UserTransformer) : null;
                    }
                }
            }
        }

        if ($deviceSuggestion->relationLoaded('vybeChangeRequest')) {
            $vybeChangeRequest = $deviceSuggestion->vybeChangeRequest;

            if ($vybeChangeRequest) {
                if ($vybeChangeRequest->relationLoaded('vybe')) {
                    $vybe = $vybeChangeRequest->vybe;

                    if ($vybe->relationLoaded('user')) {
                        $user = $vybe->user;

                        return $user ? $this->item($user, new UserTransformer) : null;
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return Item|null
     */
    public function includeVybe(DeviceSuggestion $deviceSuggestion) : ?Item
    {
        if ($deviceSuggestion->relationLoaded('vybePublishRequest')) {
            $vybePublishRequest = $deviceSuggestion->vybePublishRequest;

            if ($vybePublishRequest) {
                if ($vybePublishRequest->relationLoaded('vybe')) {
                    $vybe = $vybePublishRequest->vybe;

                    return $vybe ? $this->item($vybe, new VybeTransformer) : null;
                }
            }
        }

        if ($deviceSuggestion->relationLoaded('vybeChangeRequest')) {
            $vybeChangeRequest = $deviceSuggestion->vybeChangeRequest;

            if ($vybeChangeRequest) {
                if ($vybeChangeRequest->relationLoaded('vybe')) {
                    $vybe = $vybeChangeRequest->vybe;

                    return $vybe ? $this->item($vybe, new VybeTransformer) : null;
                }
            }
        }

        return null;
    }

    public function includeActivity(DeviceSuggestion $deviceSuggestion) : ?Item
    {
        if ($deviceSuggestion->relationLoaded('vybePublishRequest')) {
            $vybePublishRequest = $deviceSuggestion->vybePublishRequest;

            if ($vybePublishRequest) {
                if ($vybePublishRequest->relationLoaded('vybe')) {
                    $vybe = $vybePublishRequest->vybe;

                    if ($vybe->relationLoaded('activity')) {
                        $activity = $vybe->activity;

                        return $activity ? $this->item($activity, new ActivityTransformer) : null;
                    }
                }

                if ($vybePublishRequest->relationLoaded('csauSuggestions')) {
                    $csauSuggestions = $vybePublishRequest->csauSuggestions;

                    /** @var CsauSuggestion $csauSuggestion */
                    foreach ($csauSuggestions as $csauSuggestion) {
                        if ($csauSuggestion->activity) {
                            $activity = $csauSuggestion->activity;

                            return $this->item($activity, new ActivityTransformer);
                        }
                    }
                }
            }
        }

        if ($deviceSuggestion->relationLoaded('vybeChangeRequest')) {
            $vybeChangeRequest = $deviceSuggestion->vybeChangeRequest;

            if ($vybeChangeRequest) {
                if ($vybeChangeRequest->relationLoaded('vybe')) {
                    $vybe = $vybeChangeRequest->vybe;

                    if ($vybe->relationLoaded('activity')) {
                        $activity = $vybe->activity;

                        return $activity ? $this->item($activity, new ActivityTransformer) : null;
                    }
                }

                if ($vybeChangeRequest->relationLoaded('csauSuggestions')) {
                    $csauSuggestions = $vybeChangeRequest->csauSuggestions;

                    /** @var CsauSuggestion $csauSuggestion */
                    foreach ($csauSuggestions as $csauSuggestion) {
                        if ($csauSuggestion->activity) {
                            $activity = $csauSuggestion->activity;

                            return $this->item($activity, new ActivityTransformer);
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return Item|null
     */
    public function includeDevice(DeviceSuggestion $deviceSuggestion) : ?Item
    {
        $device = $deviceSuggestion->device;

        return $device ? $this->item($device, new DeviceTransformer) : null;
    }

    /**
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return Item|null
     */
    public function includeStatus(DeviceSuggestion $deviceSuggestion) : ?Item
    {
        $status = $deviceSuggestion->getStatus();

        return $status ? $this->item($status, new RequestStatusTransformer) : null;
    }

    /**
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return Item|null
     */
    public function includeAdmin(DeviceSuggestion $deviceSuggestion) : ?Item
    {
        $admin = $deviceSuggestion->admin;

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param DeviceSuggestion $deviceSuggestion
     *
     * @return string|null
     */
    private function getActivitySuggestion(DeviceSuggestion $deviceSuggestion) : ?string
    {
        if ($deviceSuggestion->relationLoaded('vybePublishRequest')) {
            $vybePublishRequest = $deviceSuggestion->vybePublishRequest;

            if ($vybePublishRequest) {
                if ($vybePublishRequest->relationLoaded('csauSuggestions')) {
                    $csauSuggestions = $vybePublishRequest->csauSuggestions;

                    /** @var CsauSuggestion $csauSuggestion */
                    foreach ($csauSuggestions as $csauSuggestion) {
                        if ($csauSuggestion->activity_suggestion) {
                            return $csauSuggestion->activity_suggestion;
                        }
                    }
                }
            }
        }

        if ($deviceSuggestion->relationLoaded('vybeChangeRequest')) {
            $vybeChangeRequest = $deviceSuggestion->vybeChangeRequest;

            if ($vybeChangeRequest) {
                if ($vybeChangeRequest->relationLoaded('csauSuggestions')) {
                    $csauSuggestions = $vybeChangeRequest->csauSuggestions;

                    /** @var CsauSuggestion $csauSuggestion */
                    foreach ($csauSuggestions as $csauSuggestion) {
                        if ($csauSuggestion->activity_suggestion) {
                            return $csauSuggestion->activity_suggestion;
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'device_suggestion';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'device_suggestions';
    }
}
