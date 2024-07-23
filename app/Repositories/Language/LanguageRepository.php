<?php

namespace App\Repositories\Language;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageListItem;
use App\Lists\Language\Level\LanguageLevelListItem;
use App\Models\MySql\Language;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Language\Interfaces\LanguageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class LanguageRepository
 *
 * @package App\Repositories\Language
 */
class LanguageRepository extends BaseRepository implements LanguageRepositoryInterface
{
    /**
     * LanguageRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.language.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return Language|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?Language
    {
        try {
            return Language::query()
                ->with([
                    'user'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/language.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param LanguageListItem $languageListItem
     *
     * @return Language|null
     *
     * @throws DatabaseException
     */
    public function findByIdUserAndLanguage(
        User $user,
        LanguageListItem $languageListItem
    ) : ?Language
    {
        try {
            return Language::query()
                ->where('user_id', '=', $user->id)
                ->where('language_id', '=', $languageListItem->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/language.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAll() : Collection
    {
        try {
            return Language::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/language.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null
    ) : LengthAwarePaginator
    {
        try {
            return Language::query()
                ->with([
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/language.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getAllByUser(
        User $user
    ) : Collection
    {
        try {
            return Language::query()
                ->where('user_id', '=', $user->id)
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/language.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param LanguageListItem $languageListItem
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsForUser(
        User $user,
        LanguageListItem $languageListItem
    ) : bool
    {
        try {
            return Language::query()
                ->where('user_id', '=', $user->id)
                ->where('language_id', '=', $languageListItem->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/language.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param LanguageListItem $languageListItem
     * @param LanguageLevelListItem $languageLevelListItem
     *
     * @return Language|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        LanguageListItem $languageListItem,
        LanguageLevelListItem $languageLevelListItem
    ) : ?Language
    {
        try {
            return Language::query()->create([
                'user_id'     => $user->id,
                'language_id' => $languageListItem->id,
                'level_id'    => $languageLevelListItem->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/language.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Language $language
     * @param User|null $user
     * @param LanguageListItem|null $languageListItem
     * @param LanguageLevelListItem|null $languageLevelListItem
     *
     * @return Language
     *
     * @throws DatabaseException
     */
    public function update(
        Language $language,
        ?User $user,
        ?LanguageListItem $languageListItem,
        ?LanguageLevelListItem $languageLevelListItem
    ) : Language
    {
        try {
            $language->update([
                'user_id'     => $user ? $user->id : $language->user_id,
                'language_id' => $languageListItem ? $languageListItem->id : $language->language_id,
                'level_id'    => $languageLevelListItem ? $languageLevelListItem->id : $language->level_id
            ]);

            return $language;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/language.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Language $language
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        Language $language
    ) : bool
    {
        try {
            return $language->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/language.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForUser(
        User $user
    ) : bool
    {
        try {
            return Language::query()
                ->where('user_id', '=', $user->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/language.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}