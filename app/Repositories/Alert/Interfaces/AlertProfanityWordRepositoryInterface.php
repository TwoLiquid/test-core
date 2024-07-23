<?php

namespace App\Repositories\Alert\Interfaces;

use App\Models\MySql\Alert\AlertProfanityFilter;
use App\Models\MySql\Alert\AlertProfanityWord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface AlertProfanityWordRepositoryInterface
 *
 * @package App\Repositories\Alert\Interfaces
 */
interface AlertProfanityWordRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return AlertProfanityWord|null
     */
    public function findById(
        ?int $id
    ) : ?AlertProfanityWord;

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
     * @param AlertProfanityFilter $alertProfanityFilter
     * @param string $word
     *
     * @return AlertProfanityWord|null
     */
    public function store(
        AlertProfanityFilter $alertProfanityFilter,
        string $word
    ) : ?AlertProfanityWord;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param AlertProfanityWord $alertProfanityWord
     * @param AlertProfanityFilter $alertProfanityFilter
     * @param string $word
     *
     * @return AlertProfanityWord
     */
    public function update(
        AlertProfanityWord $alertProfanityWord,
        AlertProfanityFilter $alertProfanityFilter,
        string $word
    ) : AlertProfanityWord;

    /**
     * This method provides deleting existing rows
     * with an eloquent model
     *
     * @param AlertProfanityFilter $alertProfanityFilter
     *
     * @return bool
     */
    public function deleteAllByFilter(
        AlertProfanityFilter $alertProfanityFilter
    ) : bool;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param AlertProfanityWord $alertProfanityWord
     *
     * @return bool
     */
    public function delete(
        AlertProfanityWord $alertProfanityWord
    ) : bool;
}
