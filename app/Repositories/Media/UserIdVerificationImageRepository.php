<?php

namespace App\Repositories\Media;

use App\Exceptions\DatabaseException;
use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Models\MySql\Media\UserIdVerificationImage;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\Media\Interfaces\UserIdVerificationImageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class UserIdVerificationImageRepository
 *
 * @package App\Repositories\Media
 */
class UserIdVerificationImageRepository extends BaseRepository implements UserIdVerificationImageRepositoryInterface
{
    /**
     * UserIdVerificationImageRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userIdVerificationImage.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return UserIdVerificationImage|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?UserIdVerificationImage
    {
        try {
            return UserIdVerificationImage::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userIdVerificationImage.' . __FUNCTION__),
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
            return UserIdVerificationImage::query()
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userIdVerificationImage.' . __FUNCTION__),
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
            return UserIdVerificationImage::query()
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userIdVerificationImage.' . __FUNCTION__),
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
    public function getByUser(
        User $user
    ) : Collection
    {
        try {
            return UserIdVerificationImage::query()
                ->where('auth_id', '=', $user->auth_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userIdVerificationImage.' . __FUNCTION__),
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
            return UserIdVerificationImage::query()
                ->whereIn('auth_id', $users->pluck('auth_id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userIdVerificationImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByRequest(
        UserIdVerificationRequest $userIdVerificationRequest
    ) : Collection
    {
        try {
            return UserIdVerificationImage::query()
                ->where('request_id', '=', $userIdVerificationRequest->_id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userIdVerificationImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Collection $userIdVerificationRequests
     *
     * @return Collection
     *
     * @throws DatabaseException
     */
    public function getByRequests(
        Collection $userIdVerificationRequests
    ) : Collection
    {
        try {
            return UserIdVerificationImage::query()
                ->whereIn('request_id', $userIdVerificationRequests->pluck('_id')
                    ->values()
                    ->toArray()
                )
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userIdVerificationImage.' . __FUNCTION__),
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
            return UserIdVerificationImage::query()
                ->whereIn('id', $ids)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/media/userIdVerificationImage.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}