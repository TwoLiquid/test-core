<?php

namespace App\Repositories\PersonalityTrait;

use App\Exceptions\DatabaseException;
use App\Models\MySql\PersonalityTrait\PersonalityTrait;
use App\Models\MySql\PersonalityTrait\PersonalityTraitVote;
use App\Models\MySql\User\User;
use App\Repositories\BaseRepository;
use App\Repositories\PersonalityTrait\Interfaces\PersonalityTraitVoteRepositoryInterface;
use Exception;

/**
 * Class PersonalityTraitVoteRepository
 *
 * @package App\Repositories\PersonalityTrait
 */
class PersonalityTraitVoteRepository extends BaseRepository implements PersonalityTraitVoteRepositoryInterface
{
    /**
     * PersonalityTraitVoteRepository constructor
     */
    public function __construct()
    {
        $this->perPage = config('repositories.personalityTraitVote.perPage');
    }

    /**
     * @param int|null $id
     *
     * @return PersonalityTraitVote|null
     *
     * @throws DatabaseException
     */
    public function findById(
        ?int $id
    ) : ?PersonalityTraitVote
    {
        try {
            return PersonalityTraitVote::query()->find($id);
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTraitVote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PersonalityTrait $personalityTrait
     * @param User $voter
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function existsForVoter(
        PersonalityTrait $personalityTrait,
        User $voter
    ) : bool
    {
        try {
            return PersonalityTraitVote::query()
                ->where('personality_trait_id', '=', $personalityTrait->id)
                ->where('voter_id', '=', $voter->id)
                ->exists();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTraitVote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PersonalityTrait $personalityTrait
     * @param User $voter
     *
     * @return PersonalityTraitVote|null
     *
     * @throws DatabaseException
     */
    public function store(
        PersonalityTrait $personalityTrait,
        User $voter
    ) : ?PersonalityTraitVote
    {
        try {
            $personalityTraitVote = PersonalityTraitVote::query()->create([
                'personality_trait_id' => $personalityTrait->id,
                'voter_id'             => $voter->id
            ]);

            $personalityTrait->votes++;
            $personalityTrait->save();

            return $personalityTraitVote;
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTraitVote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PersonalityTrait $personalityTrait
     * @param User $voter
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function deleteForVoter(
        PersonalityTrait $personalityTrait,
        User $voter
    ) : bool
    {
        try {
            $personalityTrait->votes--;
            $personalityTrait->save();

            return PersonalityTraitVote::query()
                ->where('personality_trait_id', '=', $personalityTrait->id)
                ->where('voter_id', '=', $voter->id)
                ->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTraitVote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param PersonalityTraitVote $personalityTraitVote
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function delete(
        PersonalityTraitVote $personalityTraitVote
    ) : bool
    {
        try {
            return $personalityTraitVote->delete();
        } catch (Exception $exception) {
            throw new DatabaseException(
                trans('exceptions/repository/personalityTrait/personalityTraitVote.' . __FUNCTION__),
                $exception->getMessage()
            );
        }
    }
}