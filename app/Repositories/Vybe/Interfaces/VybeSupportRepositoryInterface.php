<?php

namespace App\Repositories\Vybe\Interfaces;

use App\Models\MongoDb\Vybe\VybeSupport;
use App\Models\MySql\Category;
use App\Models\MySql\Vybe\Vybe;

/**
 * Interface VybeSupportRepositoryInterface
 *
 * @package App\Repositories\Vybe\Interfaces
 */
interface VybeSupportRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string|null $id
     *
     * @return VybeSupport|null
     */
    public function findById(
        ?string $id
    ) : ?VybeSupport;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Vybe $vybe
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param string|null $activitySuggestion
     * @param string|null $deviceSuggestion
     * @param array|null $devicesIds
     *
     * @return VybeSupport|null
     */
    public function store(
        Vybe $vybe,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?string $activitySuggestion,
        ?string $deviceSuggestion,
        ?array $devicesIds
    ) : ?VybeSupport;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param VybeSupport $vybeSupport
     * @param Category|null $category
     * @param string|null $categorySuggestion
     * @param Category|null $subcategory
     * @param string|null $subcategorySuggestion
     * @param string|null $activitySuggestion
     * @param string|null $deviceSuggestion
     * @param array|null $devicesIds
     *
     * @return VybeSupport
     */
    public function update(
        VybeSupport $vybeSupport,
        ?Category $category,
        ?string $categorySuggestion,
        ?Category $subcategory,
        ?string $subcategorySuggestion,
        ?string $activitySuggestion,
        ?string $deviceSuggestion,
        ?array $devicesIds
    ) : VybeSupport;

    /**
     * This method provides deleting existing rows
     * with an eloquent model
     *
     * @param Vybe $vybe
     *
     * @return bool
     */
    public function deleteForVybe(
        Vybe $vybe
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param VybeSupport $vybeSupport
     *
     * @return bool
     */
    public function delete(
        VybeSupport $vybeSupport
    ) : bool;
}
