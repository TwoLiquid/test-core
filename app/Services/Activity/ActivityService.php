<?php

namespace App\Services\Activity;

use App\Exceptions\BaseException;
use App\Exceptions\DatabaseException;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Activity\ActivityTag;
use App\Models\MySql\CartItem;
use App\Models\MySql\Category;
use App\Models\MySql\Order\OrderItem;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Repositories\Activity\ActivityRepository;
use App\Services\Activity\Interfaces\ActivityServiceInterface;
use App\Services\File\MediaService;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ActivityService
 *
 * @package App\Services\Activity
 */
class ActivityService implements ActivityServiceInterface
{
    /**
     * @var ActivityRepository
     */
    protected ActivityRepository $activityRepository;

    /**
     * @var ActivityTagService
     */
    protected ActivityTagService $activityTagService;

    /**
     * @var MediaService
     */
    protected MediaService $mediaService;

    /**
     * ActivityService constructor
     */
    public function __construct()
    {
        /** @var ActivityRepository activityRepository */
        $this->activityRepository = new ActivityRepository();

        /** @var ActivityTagService activityTagService */
        $this->activityTagService = new ActivityTagService();

        /** @var MediaService mediaService */
        $this->mediaService = new MediaService();
    }

    /**
     * @param Collection $activities
     * @param array $favoriteActivitiesIds
     *
     * @return Collection
     */
    public function addFavoriteProperty(
        Collection $activities,
        array $favoriteActivitiesIds
    ) : Collection
    {
        /** @var Activity $activity */
        foreach ($activities as $activity) {
            if (!in_array($activity->id, $favoriteActivitiesIds)) {
                continue;
            }

            $activity['is_favorite'] = true;
        }

        return $activities;
    }

    /**
     * @param array $files
     *
     * @throws BaseException
     */
    public function validateActivityImages(
        array $files
    ) : void
    {
        /** @var array $image */
        foreach ($files as $image) {

            /**
             * Validating activity image
             */
            $this->mediaService->validateActivityImage(
                $image['content'],
                $image['mime']
            );
        }
    }

    /**
     * @param Activity $activity
     * @param array $unitsItems
     *
     * @return void
     *
     * @throws DatabaseException
     */
    public function updateAttachedUnitsPositions(
        Activity $activity,
        array $unitsItems
    ) : void
    {
        $unitsIds = null;

        /** @var array $unitItem */
        foreach ($unitsItems as $unitItem) {
            $unitsIds[$unitItem['id']] = [
                'position' => $unitItem['position']
            ];
        }

        /**
         * Updating attached units positions
         */
        $this->activityRepository->updateUnitPositions(
            $activity,
            $unitsIds
        );
    }

    /**
     * @param array $activitiesItems
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function updatePositions(
        array $activitiesItems
    ) : Collection
    {
        $activities = new Collection();

        /** @var array $activityItem */
        foreach ($activitiesItems as $activityItem) {

            /**
             * Getting activity
             */
            $activity = $this->activityRepository->findById(
                $activityItem['id']
            );

            /**
             * Updating activity position
             */
            $this->activityRepository->updatePosition(
                $activity,
                $activityItem['position']
            );

            /**
             * Add activity to a collection
             */
            $activities->add(
                $activity
            );
        }

        return $activities;
    }

    /**
     * @param Collection $activities
     * @param Collection $activityTags
     *
     * @return Collection
     *
     * @throws BaseException
     * @throws DatabaseException
     */
    public function attachActivityTags(
        Collection $activities,
        Collection $activityTags
    ) : Collection
    {
        /** @var Activity $activity */
        foreach ($activities as $activity) {

            /** @var ActivityTag $activityTag */
            foreach ($activityTags as $activityTag) {

                /**
                 * Checking activity and activity tag relation
                 */
                if (!$this->activityTagService->checkRelatedActivity(
                    $activityTag,
                    $activity
                )) {
                    throw new BaseException(
                        trans('exceptions/service/activity/activity.' . __FUNCTION__ . '.related'),
                        null,
                        422
                    );
                }
            }

            /**
             * Attaching activity tags to activity
             */
            $this->activityRepository->attachActivityTags(
                $activity,
                $activityTags->pluck('id')->toArray(),
                true
            );
        }

        return $this->activityRepository->getByIds(
            $activities->pluck('id')
                ->toArray()
        );
    }

    /**
     * @param Category $category
     *
     * @return Collection
     */
    public function getByCategory(
        Category $category
    ) : Collection
    {
        $activities = new Collection();

        if ($category->relationLoaded('activities')) {

            /** @var Activity $activity */
            foreach ($category->activities as $activity) {
                $activities->push(
                    $activity
                );
            }
        }

        if ($category->relationLoaded('subcategories')) {

            /** @var Category $subcategory */
            foreach ($category->subcategories as $subcategory) {
                if ($subcategory->relationLoaded('activities')) {

                    /** @var Activity $activity */
                    foreach ($subcategory->activities as $activity) {
                        $activities->push(
                            $activity
                        );
                    }
                }
            }
        }

        return $activities;
    }

    /**
     * @param Category $category
     *
     * @return int
     */
    public function getCountByCategory(
        Category $category
    ) : int
    {
        $activitiesCount = $category->activities_count;

        if ($category->relationLoaded('subcategories')) {

            /** @var Category $subcategory */
            foreach ($category->subcategories as $subcategory) {
                $activitiesCount += $subcategory->activities_count;
            }
        }

        return $activitiesCount;
    }

    /**
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getByOrderItems(
        Collection $orderItems
    ) : Collection
    {
        $activities = new Collection();

        /** @var OrderItem $orderItem */
        foreach ($orderItems as $orderItem) {
            if ($orderItem->relationLoaded('vybe')) {
                if ($orderItem->vybe->relationLoaded('activity')) {
                    $activities->push(
                        $orderItem->vybe
                            ->activity
                    );
                }
            }
        }

        return $activities;
    }

    /**
     * @param Collection $cartItems
     *
     * @return Collection
     */
    public function getByCartItems(
        Collection $cartItems
    ) : Collection
    {
        $activities = new Collection();

        /** @var CartItem $cartItem */
        foreach ($cartItems as $cartItem) {
            if ($cartItem->relationLoaded('appearanceCase')) {
                $appearanceCase = $cartItem->appearanceCase;

                if ($appearanceCase->relationLoaded('vybe')) {
                    $vybe = $appearanceCase->vybe;

                    if ($vybe->relationLoaded('activity')) {
                        $activities->push(
                            $vybe->activity
                        );
                    }
                }
            }
        }

        return $activities;
    }

    /**
     * @param Collection $vybes
     *
     * @return Collection
     */
    public function getByVybes(
        Collection $vybes
    ) : Collection
    {
        $activities = new Collection();

        /** @var Vybe $vybe */
        foreach ($vybes as $vybe) {
            if ($vybe->relationLoaded('activity')) {
                $activities->push(
                    $vybe->activity
                );
            }
        }

        return $activities;
    }

    /**
     * @param Collection $users
     *
     * @return Collection
     */
    public function getByFullProfile(
        Collection $users
    ) : Collection
    {
        $responseUsers = new Collection();

        /** @var User $user */
        foreach ($users as $user) {
            if ($user->relationLoaded('activities')) {

                /** @var User $activity */
                foreach ($user->activities as $activity) {
                    $responseUsers->push(
                        $activity
                    );
                }
            }

            if ($user->relationLoaded('favoriteActivities')) {

                /** @var Activity $favoriteActivity */
                foreach ($user->favoriteActivities as $favoriteActivity) {
                    $responseUsers->push(
                        $favoriteActivity
                    );
                }
            }

            $responseUsers->push(
                $user
            );
        }

        return $responseUsers;
    }
}
