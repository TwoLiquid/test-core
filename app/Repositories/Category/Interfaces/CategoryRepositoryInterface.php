<?php

namespace App\Repositories\Category\Interfaces;

use App\Models\MySql\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface CategoryRepositoryInterface
 *
 * @package App\Repositories\Category\Interfaces
 */
interface CategoryRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Category|null
     */
    public function findById(
        ?int $id
    ) : ?Category;

    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param string $name
     *
     * @return Category|null
     */
    public function findByName(
        string $name
    ) : ?Category;

    /**
     * @param Category $category
     * @param string $name
     *
     * @return Category|null
     */
    public function findSubcategoryByName(
        Category $category,
        string $name
    ) : ?Category;

    /**
     * This method provides getting a single row
     * with an eloquent model
     *
     * @param string|null $code
     *
     * @return Category|null
     */
    public function findFullSubcategoryByCode(
        ?string $code
    ) : ?Category;

    /**
     * This method provides getting a single row
     * with an eloquent model
     *
     * @param string|null $code
     *
     * @return Category|null
     */
    public function findFullByCode(
        ?string $code
    ) : ?Category;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAllForAdmin() : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllForAdminPaginated(
        ?int $page = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param Category $category
     *
     * @return Collection
     */
    public function getByCategory(
        Category $category
    ) : Collection;

    /**
     * This method provides getting rows
     * with an eloquent model by certain query
     *
     * @param array $categoriesIds
     *
     * @return Collection
     */
    public function getByCategoriesIds(
        array $categoriesIds
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model with pagination
     *
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function getAllSubcategoriesPaginated(
        ?int $page = null
    ) : LengthAwarePaginator;

    /**
     * This method provides getting all rows
     * with an eloquent model
     *
     * @return Collection
     */
    public function getAllSubcategories() : Collection;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Category|null $parentCategory
     * @param array $name
     * @param bool|null $visible
     * @param int|null $position
     *
     * @return Category|null
     */
    public function store(
        ?Category $parentCategory,
        array $name,
        ?bool $visible,
        ?int $position = 1
    ) : ?Category;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Category $category
     * @param Category|null $parentCategory
     * @param array|null $name
     * @param bool|null $visible
     * @param int|null $position
     *
     * @return Category
     */
    public function update(
        Category $category,
        ?Category $parentCategory,
        ?array $name,
        ?bool $visible,
        ?int $position
    ) : Category;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Category $category
     * @param int $position
     */
    public function updatePosition(
        Category $category,
        int $position
    ) : void;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Category $category
     *
     * @return bool
     */
    public function delete(
        Category $category
    ) : bool;
}
