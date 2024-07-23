<?php

namespace App\Services\Vybe\Interfaces;

use App\Lists\Vybe\Period\VybePeriodListItem;
use App\Lists\Vybe\Step\VybeStepListItem;
use App\Lists\Vybe\Type\VybeTypeListItem;
use App\Models\MySql\Activity\Activity;
use App\Models\MySql\Category;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface VybeServiceInterface
 *
 * @package App\Services\Vybe\Interfaces
 */
interface VybeServiceInterface
{
    /**
     * This method provides counting data
     * by related entity
     *
     * @param Category $category
     *
     * @return int
     */
    public function countCategoryActivities(
        Category $category
    ) : int;

    /**
     * This method provides getting data
     *
     * @param Collection $vybes
     *
     * @return array
     */
    public function getFiltersForCatalog(
        Collection $vybes
    ) : array;

    /**
     * This method provides getting data
     *
     * @param Collection $vybes
     *
     * @return Collection
     */
    public function getActivitiesForCatalog(
        Collection $vybes
    ) : Collection;

    /**
     * This method provides checking data
     *
     * @param array $files
     */
    public function validateVybeAlbum(
        array $files
    ) : void;

    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function checkIsOnPublishRequest(
        Vybe $vybe
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     * @param User $user
     *
     *
     * @return bool
     */
    public function isOwner(
        Vybe $vybe,
        User $user
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param array $appearanceCases
     *
     * @return bool
     */
    public function validateAppearanceCases(
        array $appearanceCases
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param VybeTypeListItem $vybeTypeListItem
     * @param array $schedules
     *
     * @return bool
     */
    public function validateSchedules(
        VybeTypeListItem $vybeTypeListItem,
        array $schedules
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param array $files
     *
     * @return bool
     */
    public function validateFiles(
        array $files
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param array|null $uploadFiles
     * @param array|null $imagesIds
     * @param array|null $videosIds
     * @param array|null $deletedImagesIds
     * @param array|null $deletedVideosIds
     *
     * @return bool
     */
    public function checkFilesUploadAvailability(
        ?array $uploadFiles,
        ?array $imagesIds,
        ?array $videosIds,
        ?array $deletedImagesIds,
        ?array $deletedVideosIds
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param array $files
     *
     * @return array
     */
    public function getImagesFromFiles(
        array $files
    ) : array;

    /**
     * This method provides getting data
     *
     * @param array $files
     *
     * @return array
     */
    public function getVideosFromFiles(
        array $files
    ) : array;

    /**
     * This method provides getting data
     *
     * @param VybePeriodListItem $vybePeriodListItem
     * @param int $userCount
     *
     * @return VybeTypeListItem
     */
    public function getVybeTypeByParameters(
        VybePeriodListItem $vybePeriodListItem,
        int $userCount
    ) : VybeTypeListItem;

    /**
    This method provides getting data
     *
     * @param array|null $imagesIds
     * @param array|null $deletedImagesIds
     * @param array|null $uploadedImagesIds
     *
     * @return array
     */
    public function getChangedMediaIds(
        ?array $imagesIds,
        ?array $deletedImagesIds,
        ?array $uploadedImagesIds
    ) : array;

    /**
     * This method provides deleting data
     *
     * @param Vybe $vybe
     */
    public function deleteAppearanceCasesForVybe(
        Vybe $vybe
    ) : void;

    /**
     * This method provides deleting data
     *
     * @param Vybe $vybe
     */
    public function deleteAllVybeSupport(
        Vybe $vybe
    ) : void;

    /**
     * This method provides creating data
     *
     * @param Vybe $vybe
     * @param array $appearanceCases
     *
     * @return Collection
     */
    public function createAppearanceCases(
        Vybe $vybe,
        array $appearanceCases
    ) : Collection;

    /**
     * This method provides creating data
     *
     * @param Vybe $vybe
     * @param array $appearanceCases
     *
     * @return Collection
     */
    public function createAppearanceCasesWithoutSuggestions(
        Vybe $vybe,
        array $appearanceCases
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param Vybe $vybe
     * @param array $appearanceCases
     *
     * @return Collection
     */
    public function updateAppearanceCases(
        Vybe $vybe,
        array $appearanceCases
    ) : Collection;

    /**
     * This method provides deleting data
     *
     * @param Collection $vybeAppearanceCases
     * @param array $appearanceCases
     */
    public function deleteRemovedAppearanceCases(
        Collection $vybeAppearanceCases,
        array $appearanceCases
    ) : void;

    /**
     * This method provides updating data
     *
     * @param Vybe $vybe
     * @param array $appearanceCases
     *
     * @return Collection
     */
    public function updateAppearanceCasesWithoutSuggestions(
        Vybe $vybe,
        array $appearanceCases
    ) : Collection;

    /**
     * This method provides creating data
     *
     * @param Vybe $vybe
     * @param array $schedulesItems
     *
     * @return Collection
     */
    public function createSchedules(
        Vybe $vybe,
        array $schedulesItems
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param Vybe $vybe
     * @param array $schedulesItems
     *
     * @return Collection
     */
    public function updateSchedules(
        Vybe $vybe,
        array $schedulesItems
    ) : Collection;

    /**
     * This method provides checking data
     *
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param int|null $userCount
     *
     * @return VybeTypeListItem|null
     */
    public function getVybeType(
        ?VybePeriodListItem $vybePeriodListItem,
        ?int $userCount
    ) : ?VybeTypeListItem;

    /**
     * This method provides updating data
     *
     * @param Vybe $vybe
     *
     * @return Vybe
     */
    public function updateVybeType(
        Vybe $vybe
    ) : Vybe;

    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     * @param array|null $files
     *
     * @return bool
     */
    public function checkFilesExistence(
        Vybe $vybe,
        ?array $files
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     * @param VybeStepListItem $vybeStepListItem
     *
     * @return bool
     */
    public function checkStepForward(
        Vybe $vybe,
        VybeStepListItem $vybeStepListItem
    ) : bool;

    /**
     * This method provides creating data
     *
     * @param User $user
     * @param string|null $title
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param Collection|null $devices
     * @param string|null $deviceSuggestion
     * @param Activity|null $activity
     * @param string|null $activitySuggestion
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param int|null $userCount
     *
     * @return Vybe|null
     */
    public function storeFirstStep(
        User $user,
        ?string $title,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?Collection $devices,
        ?string $deviceSuggestion,
        ?Activity $activity,
        ?string $activitySuggestion,
        ?VybePeriodListItem $vybePeriodListItem,
        ?int $userCount
    ) : ?Vybe;

    /**
     * This method provides updating data
     *
     * @param Vybe $vybe
     * @param string|null $title
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param Collection|null $devices
     * @param string|null $deviceSuggestion
     * @param Activity|null $activity
     * @param string|null $activitySuggestion
     * @param VybePeriodListItem|null $vybePeriodListItem
     * @param int|null $userCount
     *
     * @return Vybe|null
     */
    public function updateFirstStep(
        Vybe $vybe,
        ?string $title,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?Collection $devices,
        ?string $deviceSuggestion,
        ?Activity $activity,
        ?string $activitySuggestion,
        ?VybePeriodListItem $vybePeriodListItem,
        ?int $userCount
    ) : ?Vybe;

    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function checkIfAnyRequestExists(
        Vybe $vybe
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Collection $orders
     *
     * @return Collection
     */
    public function getForAdminTypesByOrdersIds(
        Collection $orders
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderItems
     *
     * @return Collection
     */
    public function getForAdminTypesByOrderItemsIds(
        Collection $orderItems
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $orderInvoices
     *
     * @return Collection
     */
    public function getForAdminTypesByOrderInvoicesIds(
        Collection $orderInvoices
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $tips
     *
     * @return Collection
     */
    public function getForAdminTypesByTipsIds(
        Collection $tips
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $sales
     *
     * @return Collection
     */
    public function getForAdminTypesBySalesIds(
        Collection $sales
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Vybe $vybe
     *
     * @return mixed
     */
    public function getLatestPendingOrDeclinedRequest(
        Vybe $vybe
    ) : mixed;

    /**
     * This method provides getting data
     *
     * @param Collection $vybes
     *
     * @return Collection
     */
    public function getForAdminStatusesByIds(
        Collection $vybes
    ) : Collection;

    /**
     * This method provides updating data
     *
     * @param Vybe $vybe
     * @param array $settings
     */
    public function updateVybeCustomSettings(
        Vybe $vybe,
        array $settings
    ) : void;

    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     * @param string $accessPassword
     *
     * @return bool
     */
    public function checkAccessPassword(
        Vybe $vybe,
        string $accessPassword
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param VybeTypeListItem $vybeTypeListItem
     * @param int $userCount
     *
     * @return bool
     */
    public function isUserCountNotAllowed(
        VybeTypeListItem $vybeTypeListItem,
        int $userCount
    ) : bool;

    /**
     * This method provides checking data
     *
     * @param Vybe $vybe
     * @param User $user
     *
     * @return bool
     */
    public function checkUserAgeLimit(
        Vybe $vybe,
        User $user
    ) : bool;

    /**
     * This method provides getting data
     *
     * @param Collection $categories
     *
     * @return Collection
     */
    public function getByCategories(
        Collection $categories
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
     * @param Activity $activity
     *
     * @return Collection
     */
    public function getByActivity(
        Activity $activity
    ) : Collection;

    /**
     * This method provides getting data
     *
     * @param Collection $users
     *
     * @return Collection
     */
    public function getByUsers(
        Collection $users
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

    /**
     * This method provides getting data
     *
     * @param Vybe $vybe
     * @param Carbon $startDate
     * @param int|null $offset
     *
     * @return array
     */
    public function getScheduledCalendar(
        Vybe $vybe,
        Carbon $startDate,
        ?int $offset
    ) : array;

    /**
     * This method provides getting data
     *
     * @param Vybe $vybe
     * @param Carbon $startDate
     * @param int|null $offset
     *
     * @return array
     */
    public function getSoloCalendarForOrder(
        Vybe $vybe,
        Carbon $startDate,
        ?int $offset
    ) : array;

    /**
     * This method provides getting data
     *
     * @param Vybe $vybe
     * @param Carbon $startDate
     * @param User|null $authUser
     * @param int|null $offset
     *
     * @return array
     */
    public function getGroupCalendarForOrder(
        Vybe $vybe,
        Carbon $startDate,
        ?User $authUser,
        ?int $offset
    ) : array;

    /**
     * This method provides getting data
     *
     * @param Vybe $vybe
     * @param User|null $authUser
     * @param int|null $offset
     *
     * @return array
     */
    public function getEventCalendarForOrder(
        Vybe $vybe,
        ?User $authUser,
        ?int $offset
    ) : array;

    /**
     * This method provides updating data
     *
     * @param array $calendar
     * @param bool $isMonth
     *
     * @return array
     */
    public function completeCalendarDays(
        array $calendar,
        bool $isMonth
    ) : array;

    /**
     * This method provides sending data
     *
     * @param User $user
     * @param VybeTypeListItem $vybeTypeListItem
     *
     * @return void
     */
    public function sendToAllFollowers(
        User $user,
        VybeTypeListItem $vybeTypeListItem
    ) : void;

    /**
     * This method provides deleting data
     *
     * @param Vybe $vybe
     */
    public function delete(
        Vybe $vybe
    ) : void;
}
