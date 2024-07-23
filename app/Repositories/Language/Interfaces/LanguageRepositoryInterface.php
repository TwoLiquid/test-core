<?php

namespace App\Repositories\Language\Interfaces;

use App\Lists\Language\LanguageListItem;
use App\Lists\Language\Level\LanguageLevelListItem;
use App\Models\MySql\Language;
use App\Models\MySql\User\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface LanguageRepositoryInterface
 *
 * @package App\Repositories\Language\Interfaces
 */
interface LanguageRepositoryInterface
{
    /**
     * This method provides finding a single row
     * with an eloquent model by primary key
     *
     * @param int|null $id
     *
     * @return Language|null
     */
    public function findById(
        ?int $id
    ) : ?Language;

    /**
     * This method provides finding a single row
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param LanguageListItem $languageListItem
     *
     * @return Language|null
     */
    public function findByIdUserAndLanguage(
        User $user,
        LanguageListItem $languageListItem
    ) : ?Language;

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
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     *
     * @return Collection
     */
    public function getAllByUser(
        User $user
    ) : Collection;

    /**
     * This method provides getting all rows
     * with an eloquent model by certain query
     *
     * @param User $user
     * @param LanguageListItem $languageListItem
     *
     * @return bool
     */
    public function existsForUser(
        User $user,
        LanguageListItem $languageListItem
    ) : bool;

    /**
     * This method provides creating a new row
     * with an eloquent model
     *
     * @param User $user
     * @param LanguageListItem $languageListItem
     * @param LanguageLevelListItem $languageLevelListItem
     *
     * @return Language|null
     */
    public function store(
        User $user,
        LanguageListItem $languageListItem,
        LanguageLevelListItem $languageLevelListItem
    ) : ?Language;

    /**
     * This method provides updating existing row
     * with an eloquent model
     *
     * @param Language $language
     * @param User|null $user
     * @param LanguageListItem|null $languageListItem
     * @param LanguageLevelListItem|null $languageLevelListItem
     *
     * @return Language
     */
    public function update(
        Language $language,
        ?User $user,
        ?LanguageListItem $languageListItem,
        ?LanguageLevelListItem $languageLevelListItem
    ) : Language;

    /**
     * This method provides deleting existing row
     * with an eloquent model
     *
     * @param Language $language
     *
     * @return bool
     */
    public function delete(
        Language $language
    ) : bool;

    /**
     * This method provides deleting existing rows
     * with an eloquent model
     *
     * @param User $user
     *
     * @return bool
     */
    public function deleteForUser(
        User $user
    ) : bool;
}
