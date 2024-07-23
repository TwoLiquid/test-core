<?php

namespace App\Repositories\Media\Interfaces;

use App\Models\MySql\Category;
use App\Models\MySql\Media\CategoryIcon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface CategoryIconRepositoryInterface
 *
 * @package App\Repositories\Media\Interfaces
 */
interface CategoryIconRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return CategoryIcon|null
     */
    public function findById(
        ?int $id
    ) : ?CategoryIcon;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param Category $category
     *
     * @return CategoryIcon|null
     */
    public function findByCategory(
        Category $category
    ) : ?CategoryIcon;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page,
        ?int $perPage
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param Collection $categories
     *
     * @return Collection
     */
    public function getByCategories(
        Collection $categories
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by query
     *
     * @param array $ids
     *
     * @return Collection
     */
    public function getByIds(
        array $ids
    ) : Collection;
}
