<?php

namespace App\Services\Category\Interfaces;

use App\Models\MySql\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface CategoryServiceInterface
 *
 * @package App\Services\Category\Interfaces
 */
interface CategoryServiceInterface
{
    /**
     * This method provides getting data
     *
     * @param Category $category
     *
     * @return Collection
     */
    public function getCategoryWithSubcategories(
        Category $category
    ) : Collection;

    /**
     * This method provides checking data
     *
     * @param Category $category
     *
     * @return bool
     */
    public function isVideoGames(
        Category $category
    ) : bool;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param array $categoryItems
     *
     * @return Collection
     */
    public function updatePositions(
        array $categoryItems
    ) : Collection;
}