<?php

namespace App\Http\Controllers\Api\General\PersonalityTrait;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\PersonalityTrait\Interfaces\PersonalityTraitVoteControllerInterface;
use App\Lists\PersonalityTrait\PersonalityTraitList;
use App\Repositories\PersonalityTrait\PersonalityTraitRepository;
use App\Repositories\PersonalityTrait\PersonalityTraitVoteRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\AuthService;
use App\Transformers\Api\General\PersonalityTrait\PersonalityTraitTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class PersonalityTraitVoteController
 *
 * @package App\Http\Controllers\Api\General\PersonalityTrait
 */
final class PersonalityTraitVoteController extends BaseController implements PersonalityTraitVoteControllerInterface
{
    /**
     * @var PersonalityTraitRepository
     */
    protected PersonalityTraitRepository $personalityTraitRepository;

    /**
     * @var PersonalityTraitVoteRepository
     */
    protected PersonalityTraitVoteRepository $personalityTraitVoteRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * PersonalityTraitVoteController constructor
     */
    public function __construct()
    {
        /** @var PersonalityTraitVoteRepository personalityTraitRepository */
        $this->personalityTraitRepository = new PersonalityTraitRepository();

        /** @var PersonalityTraitVoteRepository personalityTraitVoteRepository */
        $this->personalityTraitVoteRepository = new PersonalityTraitVoteRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param int $id
     * @param int $userId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function store(
        int $id,
        int $userId
    ) : JsonResponse
    {
        /**
         * Getting personality trait list item
         */
        $personalityTraitListItem = PersonalityTraitList::getItem($id);

        if (!$personalityTraitListItem) {
            return $this->respondWithError(
                trans('validations/api/general/personalityTrait/vote/store.result.error.find.personalityTraitListItem')
            );
        }

        /**
         * Getting user
         */
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/general/personalityTrait/vote/store.result.error.find.user')
            );
        }

        /**
         * Getting personality trait for user
         */
        $personalityTrait = $this->personalityTraitRepository->findForUser(
            $personalityTraitListItem,
            $user
        );

        if (!$personalityTrait) {
            return $this->respondWithError(
                trans('validations/api/general/personalityTrait/vote/store.result.error.find.personalityTrait')
            );
        }

        /**
         * Checking a personality trait voter existence
         */
        if ($this->personalityTraitVoteRepository->existsForVoter(
            $personalityTrait,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/personalityTrait/vote/store.result.error.existence')
            );
        }

        /**
         * Creating a personality trait vote
         */
        $this->personalityTraitVoteRepository->store(
            $personalityTrait,
            AuthService::user()
        );

        /**
         * Getting user personality traits
         */
        $personalityTraits = $this->personalityTraitRepository->getForUser(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformCollection($personalityTraits, new PersonalityTraitTransformer),
            trans('validations/api/general/personalityTrait/vote/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param int $userId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id,
        int $userId
    ) : JsonResponse
    {
        /**
         * Getting personality trait list item
         */
        $personalityTraitListItem = PersonalityTraitList::getItem($id);

        if (!$personalityTraitListItem) {
            return $this->respondWithError(
                trans('validations/api/general/personalityTrait/vote/destroy.result.error.find.personalityTraitListItem')
            );
        }

        /**
         * Getting user
         */
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/general/personalityTrait/vote/destroy.result.error.find.user')
            );
        }

        /**
         * Getting personality trait for user
         */
        $personalityTrait = $this->personalityTraitRepository->findForUser(
            $personalityTraitListItem,
            $user
        );

        if (!$personalityTrait) {
            return $this->respondWithError(
                trans('validations/api/general/personalityTrait/vote/destroy.result.error.find.personalityTrait')
            );
        }

        /**
         * Deleting personality trait for voter
         */
        if (!$this->personalityTraitVoteRepository->deleteForVoter(
            $personalityTrait,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/personalityTrait/vote/destroy.result.error.delete')
            );
        }

        /**
         * Getting user personality traits
         */
        $personalityTraits = $this->personalityTraitRepository->getForUser(
            $user
        );

        return $this->respondWithSuccess(
            $this->transformCollection($personalityTraits, new PersonalityTraitTransformer),
            trans('validations/api/general/personalityTrait/vote/destroy.result.success')
        );
    }
}
