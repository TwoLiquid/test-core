<?php

namespace App\Repositories\Alert;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Alert\AlertProfanityFilter;
use App\Models\MySql\Alert\AlertProfanityWord;
use App\Repositories\Alert\Interfaces\AlertProfanityWordRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class AlertProfanityWordRepository
 *
 * @package App\Repositories\Alert
 */
class AlertProfanityWordRepository extends BaseRepository implements AlertProfanityWordRepositoryInterface
{
    /**
     * AlertProfanityWordRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.alertProfanityWord.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return AlertProfanityWord|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?AlertProfanityWord
    {
        try {
            return AlertProfanityWord::query()
                ->with([
                    'filter'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityWord.' . __FUNCTION__),
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
            return AlertProfanityWord::query()
                ->with([
                    'filter'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityWord.' . __FUNCTION__),
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
            return AlertProfanityWord::query()
                ->with([
                    'filter'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityWord.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AlertProfanityFilter $alertProfanityFilter
     * @param string $word
     *
     * @return AlertProfanityWord|null
     *
     * @throws DatabaseException
     */
    public function store(
        AlertProfanityFilter $alertProfanityFilter,
        string $word
    ) : ?AlertProfanityWord
    {
        try {
            return AlertProfanityWord::query()->create([
                'filter_id' => $alertProfanityFilter->id,
                'word'      => $word
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityWord.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AlertProfanityWord $alertProfanityWord
     * @param AlertProfanityFilter $alertProfanityFilter
     * @param string $word
     *
     * @return AlertProfanityWord
     *
     * @throws DatabaseException
     */
    public function update(
        AlertProfanityWord $alertProfanityWord,
        AlertProfanityFilter $alertProfanityFilter,
        string $word
    ) : AlertProfanityWord
    {
        try {
            $alertProfanityWord->update([
                'profanity_id' => $alertProfanityWord->filter,
                'word'         => $word ? trim($word) : $alertProfanityWord->word
            ]);

            return $alertProfanityWord;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityWord.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AlertProfanityFilter $alertProfanityFilter
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteAllByFilter(
        AlertProfanityFilter $alertProfanityFilter
    ) : bool
    {
        try {
            return AlertProfanityWord::query()
                ->where('filter_id', '=', $alertProfanityFilter->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityWord.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AlertProfanityWord $alertProfanityWord
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        AlertProfanityWord $alertProfanityWord
    ) : bool
    {
        try {
            return $alertProfanityWord->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityWord.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}