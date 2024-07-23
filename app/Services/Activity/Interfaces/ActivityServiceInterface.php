<?php

namespace App\Services\Activity\Interfaces;

use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ActivityServiceInterface
 *
 * @package App\Services\Activity\Interfaces
 */
interface ActivityServiceInterface
{
    /**
     * This method provides updating data
     *
     * @param Collection $activities
     * @param array $favoriteActivitiesIds
     *
     * @return Collection
     */
    public function addFavoriteProperty(
        Collection $activities,
        array $favoriteActivitiesIds
    ) : Collection;

    /**
     * This method provides validating data
     *
     * @param array $files
     */
    public function validateActivityImages(
        array $files
    ) : void;

    /**
     * This method provides updating data
     *
     * @param array $activitiesItems
     *
     * @return Collection
     */
    public function updatePositions(
        array $activitiesItems
    ) : Collection;

    /**
     * This method provides attaching data
     *
     * @param Collection $activities
     * @param Collection $activityTags
     *
     * @return Collection
     */
    public function attachActivityTags(
        Collection $activities,
        Collection $activityTags
    ) : Collection;
    
    /**
     * This method provides updating data
     *
     * @param Activity $activity
     * @param array $unitsItems
     * 
     * @return void
     */
    public function updateAttachedUnitsPositions(
        Activity $activity,
        array $unitsItems
    ) : void;

    /**
     * This method provides getting data
     *
     * @param Category $category
     *
     * @return Collection
     */
    public function getByCategory(
        Category $category
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Category $category
     *
     * @return int
     */
    public function getCountByCategory(
        Category $category
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getByOrderItems(
        Collection $orderItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $cartItems
     *
     * @return Collection
     */
    public function getByCartItems(
        Collection $cartItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $vybes
     *
     * @return Collection
     */
    public function getByVybes(
        Collection $vybes
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $users
     *
     * @return Collection
     */
    public function getByFullProfile(
        Collection $users
    ) : Collection;
}