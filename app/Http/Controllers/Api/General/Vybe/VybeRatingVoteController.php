<?php

namespace App\Http\Controllers\Api\General\Vybe;

use App\Exceptions\DatabaseException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Vybe\Interfaces\VybeRatingVoteControllerInterface;
use App\Http\Requests\Api\General\Vybe\Rating\Vote\IndexRequest;
use App\Http\Requests\Api\General\Vybe\Rating\Vote\StoreRequest;
use App\Http\Requests\Api\General\Vybe\Rating\Vote\UpdateRequest;
use App\Repositories\User\UserRepository;
use App\Repositories\Vybe\VybeRatingVoteRepository;
use App\Repositories\Vybe\VybeRepository;
use App\Services\Auth\AuthService;
use App\Transformers\Api\General\Vybe\VybeRatingVote\VybeRatingVoteTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class VybeRatingVoteController
 *
 * @package App\Http\Controllers\Api\General\Vybe
 */
final class VybeRatingVoteController extends BaseController implements VybeRatingVoteControllerInterface
{
    /**
     * @var VybeRepository
     */
    protected VybeRepository $vybeRepository;

    /**
     * @var VybeRatingVoteRepository
     */
    protected VybeRatingVoteRepository $vybeRatingVoteRepository;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * VybeRatingVoteController constructor
     */
    public function __construct()
    {
        /** @var VybeRatingVoteRepository vybeRatingVoteRepository */
        $this->vybeRatingVoteRepository = new VybeRatingVoteRepository();

        /** @var VybeRepository vybeRepository */
        $this->vybeRepository = new VybeRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param IndexRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function index(
        IndexRequest $request
    ) : JsonResponse
    {
        /**
         * Checking pagination existence
         */
        if ($request->input('paginated') === true) {

            /**
             * Getting vybe rating votes with pagination
             */
            $vybeRatingVotes = $this->vybeRatingVoteRepository->getAllPaginated(
                $request->input('page')
            );

            return $this->setPagination($vybeRatingVotes)->respondWithSuccess(
                $this->transformCollection($vybeRatingVotes, new VybeRatingVoteTransformer),
                trans('validations/api/general/vybe/rating/vote/index.result.success')
            );
        }

        /**
         * Getting vybe rating votes with pagination
         */
        $vybeRatingVotes = $this->vybeRatingVoteRepository->getAll();

        return $this->respondWithSuccess(
            $this->transformCollection($vybeRatingVotes, new VybeRatingVoteTransformer),
            trans('validations/api/general/vybe/rating/vote/index.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function show(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vybe rating vote
         */
        $vybeRatingVote = $this->vybeRatingVoteRepository->findById($id);

        /**
         * Checking vybe rating vote existence
         */
        if (!$vybeRatingVote) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/rating/vote/show.result.error.find')
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybeRatingVote, new VybeRatingVoteTransformer),
            trans('validations/api/general/vybe/rating/vote/show.result.success')
        );
    }

    /**
     * @param StoreRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function store(
        StoreRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findById(
            $request->input('vybe_id')
        );

        /**
         * Checking vybe to user belonging
         */
        if (AuthService::user()->id == $vybe->user->id) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/rating/vote/store.result.error.match.user')
            );
        }

        /**
         * Getting user
         */
        $user = $this->userRepository->findById(
            $request->input('user_id')
        );

        /**
         * Checking user rating vote existence
         */
        if ($this->vybeRatingVoteRepository->checkUserRatingVoteExistence(
            $vybe,
            $user
        )) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/rating/vote/store.result.error.existence')
            );
        }

        /**
         * Getting a rating vote
         */
        $vybeRatingVote = $this->vybeRatingVoteRepository->store(
            $vybe,
            $user,
            $request->input('rating')
        );

        /**
         * Checking vybe rating vote existence
         */
        if (!$vybeRatingVote) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/rating/vote/store.result.error.create')
            );
        }

        /**
         * Updating vybe rating
         */
        $this->vybeRepository->updateRating(
            $vybe
        );

        return $this->respondWithSuccess(
            $this->transformItem($vybeRatingVote, new VybeRatingVoteTransformer),
            trans('validations/api/general/vybe/rating/vote/store.result.success')
        );
    }

    /**
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function update(
        int $id,
        UpdateRequest $request
    ) : JsonResponse
    {
        /**
         * Getting vybe rating vote
         */
        $vybeRatingVote = $this->vybeRatingVoteRepository->findById($id);

        /**
         * Checking vybe rating vote existence
         */
        if (!$vybeRatingVote) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/rating/vote/update.result.error.find')
            );
        }

        /**
         * Getting vybe
         */
        $vybe = $this->vybeRepository->findById(
            $request->input('vybe_id')
        );

        if ($vybe !== null) {

            /**
             * Checking vybe to user belonging
             */
            if (AuthService::user()->id == $vybe->user->id) {
                return $this->respondWithError(
                    trans('validations/api/general/vybe/rating/vote/update.result.error.match.user')
                );
            }

            /**
             * Getting user
             */
            $user = $this->userRepository->findById(
                $request->input('user_id')
            );

            /**
             * Getting vybe rating vote
             */
            $vybeRatingVote = $this->vybeRatingVoteRepository->update(
                $vybeRatingVote,
                $vybe,
                $user,
                $request->input('rating')
            );

            /**
             * Updating vybe rating
             */
            $this->vybeRepository->updateRating(
                $vybe
            );
        }

        return $this->respondWithSuccess(
            $this->transformItem($vybeRatingVote, new VybeRatingVoteTransformer),
            trans('validations/api/general/vybe/rating/vote/update.result.success')
        );
    }

    /**
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function destroy(
        int $id
    ) : JsonResponse
    {
        /**
         * Getting vybe rating vote
         */
        $vybeRatingVote = $this->vybeRatingVoteRepository->findById($id);

        /**
         * Checking vybe rating vote existence
         */
        if (!$vybeRatingVote) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/rating/vote/destroy.result.error.find')
            );
        }

        /**
         * Deleting vybe rating vote
         */
        if (!$this->vybeRatingVoteRepository->delete($vybeRatingVote)) {
            return $this->respondWithError(
                trans('validations/api/general/vybe/rating/vote/destroy.result.error.delete')
            );
        }

        return $this->respondWithSuccess([],
            trans('validations/api/general/vybe/rating/vote/destroy.result.success')
        );
    }
}
