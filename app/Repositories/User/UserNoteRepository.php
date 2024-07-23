<?php

namespace App\Repositories\User;

use App\Exceptions\DatabaseException;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use App\Models\MySql\User\UserNote;
use App\Repositories\BaseRepository;
use App\Repositories\User\Interfaces\UserNoteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class UserNoteRepository
 *
 * @package App\Repositories\User
 */
class UserNoteRepository extends BaseRepository implements UserNoteRepositoryInterface
{
    /**
     * UserNoteRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.userNote.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return UserNote|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?UserNote
    {
        try {
            return UserNote::query()
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userNote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int $id
     *
     * @return UserNote|null
     *
     * @throws DatabaseException
     */
    public function findForUserById(
        User $user,
        int $id
    ) : ?UserNote
    {
        try {
            return UserNote::query()
                ->where('user_id', '=', $user->id)
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userNote.' . __FUNCTION__),
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
            return UserNote::query()
                ->with([
                    'user',
                    'admin'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userNote.' . __FUNCTION__),
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
            return UserNote::query()
                ->with([
                    'user',
                    'admin'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userNote.' . __FUNCTION__),
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
    public function getForUser(
        User $user
    ) : Collection
    {
        try {
            return UserNote::query()
                ->with([
                    'user',
                    'admin'
                ])
                ->where('user_id', '=', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userNote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param int|null $page
     * @param int|null $perPage
     *
     * @return LengthAwarePaginator
     *
     * @throws DatabaseException
     */
    public function getForUserPaginated(
        User $user,
        ?int $page = null,
        ?int $perPage = null
    ) : LengthAwarePaginator
    {
        try {
            return UserNote::query()
                ->with([
                    'user',
                    'admin'
                ])
                ->where('user_id', '=', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage ?: $this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userNote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param Admin $admin
     * @param string $text
     *
     * @return UserNote|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        Admin $admin,
        string $text
    ) : ?UserNote
    {
        try {
            return UserNote::query()->create([
                'user_id'  => $user->id,
                'admin_id' => $admin->id,
                'text'     => trim($text)
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userNote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserNote $userNote
     * @param User|null $user
     * @param Admin|null $admin
     * @param string|null $text
     *
     * @return UserNote
     *
     * @throws DatabaseException
     */
    public function update(
        UserNote $userNote,
        ?User $user,
        ?Admin $admin,
        ?string $text
    ) : UserNote
    {
        try {
            $userNote->update([
                'user_id'  => $user ? $user->id : $userNote->user_id,
                'admin_id' => $admin ? $admin->id : $userNote->admin_id,
                'text'     => $text ? trim($text) : $userNote->text
            ]);

            return $userNote;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userNote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserNote $userNote
     * @param string $text
     *
     * @return UserNote
     *
     * @throws DatabaseException
     */
    public function updateText(
        UserNote $userNote,
        string $text
    ) : UserNote
    {
        try {
            $userNote->update([
                'text' => $text
            ]);

            return $userNote;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userNote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param UserNote $userNote
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        UserNote $userNote
    ) : bool
    {
        try {
            return $userNote->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/user/userNote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}
