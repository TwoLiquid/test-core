<?php

namespace App\Repositories\PersonalityTrait;

use App\Exceptions\DatabaseException;
use App\Lists\PersonalityTrait\PersonalityTraitListItem;
use App\Models\MySql\PersonalityTrait\PersonalityTrait;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\PersonalityTrait\Interfaces\PersonalityTraitRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Exception;

/**
 * Class PersonalityTraitRepository
 *
 * @package App\Repositories\PersonalityTrait
 */
class PersonalityTraitRepository extends BaseRepository implements PersonalityTraitRepositoryInterface
{
    /**
     * PersonalityTraitRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.personalityTrait.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return PersonalityTrait|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?PersonalityTrait
    {
        try {
            return PersonalityTrait::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTrait.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PersonalityTraitListItem $personalityTraitListItem
     * @param User $user
     *
     * @return PersonalityTrait|null
     *
     * @throws DatabaseException
     */
    public function findForUser(
        PersonalityTraitListItem $personalityTraitListItem,
        User $user
    ) : ?PersonalityTrait
    {
        try {
            return PersonalityTrait::query()
                ->where('trait_id', '=', $personalityTraitListItem->id)
                ->where('user_id', '=', $user->id)
                ->first();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTrait.' . __FUNCTION__),
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
            return PersonalityTrait::query()
                ->where('user_id', '=', $user->id)
                ->get();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTrait.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param PersonalityTraitListItem $personalityTraitListItem
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsForUser(
        User $user,
        PersonalityTraitListItem $personalityTraitListItem
    ) : bool
    {
        try {
            return PersonalityTrait::query()
                ->where('user_id', '=', $user->id)
                ->where('trait_id', '=', $personalityTraitListItem->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTrait.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param User $user
     * @param PersonalityTraitListItem $personalityTraitListItem
     *
     * @return PersonalityTrait|null
     *
     * @throws DatabaseException
     */
    public function store(
        User $user,
        PersonalityTraitListItem $personalityTraitListItem
    ) : ?PersonalityTrait
    {
        try {
            return PersonalityTrait::query()->create([
                'user_id'  => $user->id,
                'trait_id' => $personalityTraitListItem->id
            ]);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTrait.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PersonalityTrait $personalityTrait
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        PersonalityTrait $personalityTrait
    ) : bool
    {
        try {
            return $personalityTrait->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTrait.' . __FUNCTION__),
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
            return PersonalityTrait::query()
                ->where('user_id', '=', $user->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTrait.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}