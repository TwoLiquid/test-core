<?php

namespace App\Transformers\Api\Admin\Csau\Suggestion;

use App\Models\MongoDb\Suggestion\CsauSuggestion;
use App\Transformers\Api\Admin\Csau\Suggestion\Activity\ActivityTransformer;
use App\Transformers\Api\Admin\Csau\Suggestion\Category\CategoryTransformer;
use App\Transformers\Api\Admin\Csau\Suggestion\Category\SubcategoryTransformer;
use App\Transformers\Api\Admin\Csau\Suggestion\Unit\UnitTransformer;
use App\Transformers\Api\Admin\Csau\Suggestion\Vybe\VybeTransformer;
use App\Transformers\BaseTransformer;
use League\Fractal\Resource\Item;

/**
 * Class CsauSuggestionTransformer
 *
 * @package App\Transformers\Api\Admin\Csau\Suggestion
 */
class CsauSuggestionTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $defaultIncludes = [
        'user',
        'vybe',
        'category',
        'category_status',
        'subcategory',
        'subcategory_status',
        'activity',
        'activity_status',
        'unit',
        'unit_status',
        'status',
        'admin'
    ];

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return array
     */
    public function transform(CsauSuggestion $csauSuggestion) : array
    {
        return [
            'id'                       => $csauSuggestion->_id,
            'category_suggestion'      => $csauSuggestion->category_suggestion,
            'subcategory_suggestion'   => $csauSuggestion->subcategory_suggestion,
            'activity_suggestion'      => $csauSuggestion->activity_suggestion,
            'unit_suggestion'          => $csauSuggestion->unit_suggestion,
            'unit_suggestion_is_event' => $this->isUnitEvent($csauSuggestion),
            'visible'                  => $csauSuggestion->visible,
            'created_at'               => $csauSuggestion->created_at->format('Y-m-d\TH:i:s.v\Z')
        ];
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeUser(CsauSuggestion $csauSuggestion) : ?Item
    {
        if ($csauSuggestion->relationLoaded('vybePublishRequest')) {
            $vybePublishRequest = $csauSuggestion->vybePublishRequest;

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

        if ($csauSuggestion->relationLoaded('vybeChangeRequest')) {
            $vybeChangeRequest = $csauSuggestion->vybeChangeRequest;

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
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeVybe(CsauSuggestion $csauSuggestion) : ?Item
    {
        if ($csauSuggestion->relationLoaded('vybePublishRequest')) {
            $vybePublishRequest = $csauSuggestion->vybePublishRequest;

            if ($vybePublishRequest) {
                if ($vybePublishRequest->relationLoaded('vybe')) {
                    $vybe = $vybePublishRequest->vybe;

                    return $vybe ? $this->item($vybe, new VybeTransformer) : null;
                }
            }
        }

        if ($csauSuggestion->relationLoaded('vybeChangeRequest')) {
            $vybeChangeRequest = $csauSuggestion->vybeChangeRequest;

            if ($vybeChangeRequest) {
                if ($vybeChangeRequest->relationLoaded('vybe')) {
                    $vybe = $vybeChangeRequest->vybe;

                    return $vybe ? $this->item($vybe, new VybeTransformer) : null;
                }
            }
        }

        return null;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeCategory(CsauSuggestion $csauSuggestion) : ?Item
    {
        $category = $csauSuggestion->category;

        return $category ? $this->item($category, new CategoryTransformer) : null;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeCategoryStatus(CsauSuggestion $csauSuggestion) : ?Item
    {
        $categoryStatus = $csauSuggestion->getCategoryStatus();

        return $categoryStatus ? $this->item($categoryStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeSubcategory(CsauSuggestion $csauSuggestion) : ?Item
    {
        $subcategory = $csauSuggestion->subcategory;

        return $subcategory ? $this->item($subcategory, new SubcategoryTransformer) : null;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeSubcategoryStatus(CsauSuggestion $csauSuggestion) : ?Item
    {
        $subcategoryStatus = $csauSuggestion->getSubcategoryStatus();

        return $subcategoryStatus ? $this->item($subcategoryStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeActivity(CsauSuggestion $csauSuggestion) : ?Item
    {
        $activity = $csauSuggestion->activity;

        return $activity ? $this->item($activity, new ActivityTransformer) : null;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeActivityStatus(CsauSuggestion $csauSuggestion) : ?Item
    {
        $activityStatus = $csauSuggestion->getActivityStatus();

        return $activityStatus ? $this->item($activityStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeUnit(CsauSuggestion $csauSuggestion) : ?Item
    {
        $unit = $csauSuggestion->unit;

        return $unit ? $this->item($unit, new UnitTransformer) : null;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeUnitStatus(CsauSuggestion $csauSuggestion) : ?Item
    {
        $unitStatus = $csauSuggestion->getUnitStatus();

        return $unitStatus ? $this->item($unitStatus, new RequestFieldStatusTransformer) : null;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeStatus(CsauSuggestion $csauSuggestion) : ?Item
    {
        $status = $csauSuggestion->getStatus();

        return $status ? $this->item($status, new RequestStatusTransformer) : null;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return Item|null
     */
    public function includeAdmin(CsauSuggestion $csauSuggestion) : ?Item
    {
        $admin = $csauSuggestion->admin;

        return $admin ? $this->item($admin, new AdminTransformer) : null;
    }

    /**
     * @param CsauSuggestion $csauSuggestion
     *
     * @return bool
     */
    private function isUnitEvent(CsauSuggestion $csauSuggestion) : bool
    {
        if ($csauSuggestion->relationLoaded('vybePublishRequest')) {
            $vybePublishRequest = $csauSuggestion->vybePublishRequest;

            if ($vybePublishRequest) {
                if ($vybePublishRequest->getType()->isEvent()) {
                    return true;
                }
            }
        }

        if ($csauSuggestion->relationLoaded('vybeChangeRequest')) {
            $vybeChangeRequest = $csauSuggestion->vybeChangeRequest;

            if ($vybeChangeRequest) {
                if (($vybeChangeRequest->getType() && $vybeChangeRequest->getType()->isEvent()) ||
                    (!$vybeChangeRequest->getType() && $vybeChangeRequest->vybe->getType()->isEvent())
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'csau_suggestion';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'csau_suggestions';
    }
}
