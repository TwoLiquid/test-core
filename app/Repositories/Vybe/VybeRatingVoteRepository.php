<?php

namespace App\Repositories\Vybe;

use App\Exceptions\DatabaseException;
use App\Models\MySql\User\User;
use App\Models\MySql\Vybe\Vybe;
use App\Models\MySql\Vybe\VybeRatingVote;
use App\Repositories\BaseRepository;
use App\Repositories\Vybe\Interfaces\VybeRatingVoteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Class VybeRatingVoteRepository
 *
 * @package App\Repositories\Vybe
 */
class VybeRatingVoteRepository extends BaseRepository implements VybeRatingVoteRepositoryInterface
{
    /**
     * VybeRatingVoteRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.vybeRatingVote.perPage');
    }

    /**
     * @param int $id
     *
     * @return VybeRatingVote|null
     *
     * @throws DatabaseException
     */
    public function findById(
        int $id
    ) : ?VybeRatingVote
    {
        try {
            return VybeRatingVote::query()
                ->with([
                    'vybe',
                    'user'
                ])
                ->where('id', '=', $id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeRatingVote.' . __FUNCTION__),
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
            return VybeRatingVote::query()
                ->with([
                    'vybe',
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeRatingVote.' . __FUNCTION__),
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
            return VybeRatingVote::query()
                ->with([
                    'vybe',
                    'user'
                ])
                ->orderBy('id', 'desc')
                ->paginate($this->perPage, ['*'], 'page', $page);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeRatingVote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param User $user
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function checkUserRatingVoteExistence(
        Vybe $vybe,
        User $user
    ) : bool
    {
        try {
            return VybeRatingVote::query()
                ->where('vybe_id', '=', $vybe->id)
                ->where('user_id', '=', $user->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeRatingVote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param Vybe $vybe
     * @param User $user
     * @param int $rating
     *
     * @return VybeRatingVote|null
     *
     * @throws DatabaseException
     */
    public function store(
        Vybe $vybe,
        User $user,
        int $rating
    ) : ?VybeRatingVote
    {
        try {
            return VybeRatingVote::query()->create([
                'vybe_id' => $vybe->id,
                'user_id' => $user->id,
                'rating'  => $rating
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeRatingVote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeRatingVote $vybeRatingVote
     * @param Vybe|null $vybe
     * @param User|null $user
     * @param int|null $rating
     *
     * @return VybeRatingVote
     *
     * @throws DatabaseException
     */
    public function update(
        VybeRatingVote $vybeRatingVote,
        ?Vybe $vybe,
        ?User $user,
        ?int $rating
    ) : VybeRatingVote
    {
        try {
            $vybeRatingVote->update([
                'vybe_id' => $vybe ? $vybe->id : $vybeRatingVote->vybe_id,
                'user_id' => $user ? $user->id : $vybeRatingVote->user_id,
                'rating'  => $rating ?: $vybeRatingVote->rating
            ]);

            return $vybeRatingVote;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeRatingVote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param VybeRatingVote $vybeRatingVote
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        VybeRatingVote $vybeRatingVote
    ) : bool
    {
        try {
            return $vybeRatingVote->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/vybe/vybeRatingVote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}