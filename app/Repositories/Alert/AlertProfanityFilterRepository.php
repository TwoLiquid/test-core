<?php

namespace App\Repositories\Alert;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Alert\Alert;
use App\Models\MySql\Alert\AlertProfanityFilter;
use App\Repositories\Alert\Interfaces\AlertProfanityFilterRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class AlertProfanityFilterRepository
 *
 * @package App\Repositories\Alert
 */
class AlertProfanityFilterRepository extends BaseRepository implements AlertProfanityFilterRepositoryInterface
{
    /**
     * AlertProfanityFilterRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.alertProfanityFilter.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return AlertProfanityFilter|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?AlertProfanityFilter
    {
        try {
            return AlertProfanityFilter::query()
                ->with([
                    'alert',
                    'words'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityFilter.' . __FUNCTION__),
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
            return AlertProfanityFilter::query()
                ->with([
                    'words'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityFilter.' . __FUNCTION__),
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
            return AlertProfanityFilter::query()
                ->with([
                    'words'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityFilter.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Alert $alert
     * @param bool $replace
     * @param string $replaceWith
     * @param bool $hideMessages
     * @param bool $badWords
     *
     * @return AlertProfanityFilter|null
     *
     * @throws DatabaseException
     */
    public function store(
        Alert $alert,
        bool $replace,
        string $replaceWith,
        bool $hideMessages,
        bool $badWords
    ) : ?AlertProfanityFilter
    {
        try {
            return AlertProfanityFilter::query()->create([
                'alert_id'      => $alert->id,
                'replace'       => $replace,
                'replace_with'  => $replaceWith,
                'hide_messages' => $hideMessages,
                'bad_words'     => $badWords
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityFilter.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param AlertProfanityFilter $alertProfanityFilter
     * @param Alert|null $alert
     * @param bool|null $replace
     * @param string|null $replaceWith
     * @param bool|null $hideMessages
     * @param bool|null $badWords
     *
     * @return AlertProfanityFilter
     *
     * @throws DatabaseException
     */
    public function update(
        AlertProfanityFilter $alertProfanityFilter,
        ?Alert $alert,
        ?bool $replace,
        ?string $replaceWith,
        ?bool $hideMessages,
        ?bool $badWords
    ) : AlertProfanityFilter
    {
        try {
            $alertProfanityFilter->update([
                'alert_id'      => $alert ? $alert->id : $alertProfanityFilter->alert_id,
                'replace'       => $replace ?: $alertProfanityFilter->replace,
                'replace_with'  => $replaceWith ?: $alertProfanityFilter->replace_with,
                'hide_messages' => $hideMessages ?: $alertProfanityFilter->hide_messages,
                'bad_words'     => $badWords ?: $alertProfanityFilter->bad_words
            ]);

            return $alertProfanityFilter;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityFilter.' . __FUNCTION__),
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
    public function delete(
        AlertProfanityFilter $alertProfanityFilter
    ) : bool
    {
        try {
            return $alertProfanityFilter->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/alert/alertProfanityFilter.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}