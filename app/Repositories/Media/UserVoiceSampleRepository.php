<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Media\UserVoiceSample;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\UserVoiceSampleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class UserVoiceSampleRepository
 *
 * @package App\Repositories\Media
 */
class UserVoiceSampleRepository extends BaseRepository implements UserVoiceSampleRepositoryInterface
{
    /**
     * UserVoiceSampleRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userVoiceSample.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return UserVoiceSample|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?UserVoiceSample
    {
        try {
            return UserVoiceSample::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVoiceSample.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * 
     * @return UserVoiceSample|null
     *
     * @throws DatabaseException
     */
    public function findByUser(
        User $user
    ) : ?UserVoiceSample
    {
        try {
            return UserVoiceSample::query()
                ->where('auth_id', '=', $user->auth_id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVoiceSample.' . __FUNCTION__),
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
            return UserVoiceSample::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVoiceSample.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getAllPaginated(
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return UserVoiceSample::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVoiceSample.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $users
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByUsers(
        Collection $users
    ) : Collection
    {
        try {
            return UserVoiceSample::query()
                ->whereIn('id', $users->pluck('voice_sample_id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVoiceSample.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $userProfileRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByRequests(
        Collection $userProfileRequests
    ) : Collection
    {
        try {
            return UserVoiceSample::query()
                ->whereIn('id', $userProfileRequests->pluck('voice_sample_id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVoiceSample.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param array $ids
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByIds(
        array $ids
    ) : Collection
    {
        try {
            return UserVoiceSample::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userVoiceSample.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}