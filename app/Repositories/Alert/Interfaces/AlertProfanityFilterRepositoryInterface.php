<?php

namespace App\Repositories\Alert\Interfaces;

use App\Models\MySql\Alert\Alert;
use App\Models\MySql\Alert\AlertProfanityFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface AlertProfanityFilterRepositoryInterface
 *
 * @package App\Repositories\Alert\Interfaces
 */
interface AlertProfanityFilterRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return AlertProfanityFilter|null
     */
    public function findById(
        ?int $id
    ) : ?AlertProfanityFilter;

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
     *
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(
        ?int $page
    ) : LengthAwarePaginator;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param Alert $alert
     * @param bool $replace
     * @param string $replaceWith
     * @param bool $hideMessages
     * @param bool $badWords
     *
     * @return AlertProfanityFilter|null
     */
    public function store(
        Alert $alert,
        bool $replace,
        string $replaceWith,
        bool $hideMessages,
        bool $badWords
    ) : ?AlertProfanityFilter;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param AlertProfanityFilter $alertProfanityFilter
     * @param Alert|null $alert
     * @param bool|null $replace
     * @param string|null $replaceWith
     * @param bool|null $hideMessages
     * @param bool|null $badWords
     *
     * @return AlertProfanityFilter
     */
    public function update(
        AlertProfanityFilter $alertProfanityFilter,
        ?Alert $alert,
        ?bool $replace,
        ?string $replaceWith,
        ?bool $hideMessages,
        ?bool $badWords
    ) : AlertProfanityFilter;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param AlertProfanityFilter $alertProfanityFilter
     *
     * @return bool
     */
    public function delete(
        AlertProfanityFilter $alertProfanityFilter
    ) : bool;
}
