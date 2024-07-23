<?php

namespace App\Http\Controllers\Api\General\Profile;

use App\Exceptions\DatabaseException;
use App\Exceptions\MicroserviceException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\General\Profile\Interfaces\HomeControllerInterface;
use App\Lists\PersonalityTrait\PersonalityTraitList;
use App\Microservices\Media\MediaMicroservice;
use App\Repositories\PersonalityTrait\PersonalityTraitRepository;
use App\Repositories\PersonalityTrait\PersonalityTraitVoteRepository;
use App\Repositories\User\UserImageLikeRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserVideoLikeRepository;
use App\Services\Auth\AuthService;
use App\Transformers\Api\General\Profile\Home\UserImageLikeTransformer;
use App\Transformers\Api\General\Profile\Home\UserVideoLikeTransformer;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers\Api\Guest\Profile
 */
final class HomeController extends BaseController implements HomeControllerInterface
{
    /**
     * @var MediaMicroservice
     */
    protected MediaMicroservice $mediaMicroservice;

    /**
     * @var PersonalityTraitRepository
     */
    protected PersonalityTraitRepository $personalityTraitRepository;

    /**
     * @var PersonalityTraitVoteRepository
     */
    protected PersonalityTraitVoteRepository $personalityTraitVoteRepository;

    /**
     * @var UserImageLikeRepository
     */
    protected UserImageLikeRepository $userImageLikeRepository;

    /**
     * @var UserVideoLikeRepository
     */
    protected UserVideoLikeRepository $userVideoLikeRepository;

    /**
     * @var UserRepository
     */
    public UserRepository $userRepository;

    /**
     * HomeController constructor
     */
    public function __construct()
    {
        /** @var MediaMicroservice mediaMicroservice */
        $this->mediaMicroservice = new MediaMicroservice();

        /** @var PersonalityTraitRepository personalityTraitRepository */
        $this->personalityTraitRepository = new PersonalityTraitRepository();

        /** @var PersonalityTraitVoteRepository personalityTraitVoteRepository */
        $this->personalityTraitVoteRepository = new PersonalityTraitVoteRepository();

        /** @var UserImageLikeRepository userImageLikeRepository */
        $this->userImageLikeRepository = new UserImageLikeRepository();

        /** @var UserVideoLikeRepository userVideoLikeRepository */
        $this->userVideoLikeRepository = new UserVideoLikeRepository();

        /** @var UserRepository userRepository */
        $this->userRepository = new UserRepository();
    }

    /**
     * @param string $username
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     */
    public function personalityTraitVote(
        string $username,
        int $id
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByUsername(
            $username
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/general/profile/home/personalityTraitVote.result.error.find.user')
            );
        }

        /**
         * Getting personality trait
         */
        $personalityTraitListItem = PersonalityTraitList::getItem($id);

        /**
         * Checking personality trait existence
         */
        if (!$personalityTraitListItem) {
            return $this->respondWithError(
                trans('validations/api/general/profile/home/personalityTraitVote.result.error.find.personalityTraitListItem')
            );
        }

        /**
         * Checking is yourself
         */
        if (AuthService::user()->id == $user->id) {
            return $this->respondWithError(
                trans('validations/api/general/profile/home/personalityTraitVote.result.error.yourself')
            );
        }

        /**
         * Getting user personality trait
         */
        $personalityTrait = $this->personalityTraitRepository->findForUser(
            $personalityTraitListItem,
            $user
        );

        if (!$personalityTrait) {

            /**
             * Creating user personality trait
             */
            $personalityTrait = $this->personalityTraitRepository->store(
                $user,
                $personalityTraitListItem
            );
        }

        /**
         * Checking user a personality trait vote existence
         */
        if ($this->personalityTraitVoteRepository->existsForVoter(
            $personalityTrait,
            AuthService::user()
        )) {
            return $this->respondWithError(
                trans('validations/api/general/profile/home/personalityTraitVote.result.error.exists')
            );
        }

        /**
         * Creating a user vote
         */
        $this->personalityTraitVoteRepository->store(
            $personalityTrait,
            AuthService::user()
        );

        return $this->respondWithSuccess([],
            trans('validations/api/general/profile/home/personalityTraitVote.result.success')
        );
    }

    /**
     * @param string $username
     * @param int $imageId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function likeUserImage(
        string $username,
        int $imageId
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByUsername(
            $username
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/general/profile/home/likeUserImage.result.error.find')
            );
        }

        /**
         * Checking image to user belonging
         */
        $this->mediaMicroservice->existsImageForUser(
            $user,
            $imageId
        );

        $liked = true;

        /**
         * Checking user like existence
         */
        if ($this->userImageLikeRepository->existsForImage(
            AuthService::user(),
            $imageId
        )) {

            /**
             * Deleting user like
             */
            $this->userImageLikeRepository->deleteForImage(
                AuthService::user(),
                $imageId
            );

            $liked = false;
        } else {

            /**
             * Creating user like
             */
            $this->userImageLikeRepository->store(
                AuthService::user(),
                $imageId
            );
        }

        /**
         * Getting total likes count
         */
        $userImageLikes = $this->userImageLikeRepository->getLikesForImageCount(
            $imageId
        );

        /**
         * Updating user image likes count
         */
        $userImageResponse = $this->mediaMicroservice->updateUserImageLikes(
            $imageId,
            $userImageLikes
        );

        return $this->respondWithSuccess(
            $this->transformItem([],
                new UserImageLikeTransformer($liked, $userImageResponse)
            ), trans('validations/api/general/profile/home/likeUserImage.result.success')
        );
    }

    /**
     * @param string $username
     * @param int $videoId
     *
     * @return JsonResponse
     *
     * @throws DatabaseException
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function likeUserVideo(
        string $username,
        int $videoId
    ) : JsonResponse
    {
        /**
         * Getting user
         */
        $user = $this->userRepository->findByUsername(
            $username
        );

        /**
         * Checking user existence
         */
        if (!$user) {
            return $this->respondWithError(
                trans('validations/api/general/profile/home/likeUserVideo.result.error.find')
            );
        }

        /**
         * Checking video to user belonging
         */
        $this->mediaMicroservice->existsVideoForUser(
            $user,
            $videoId
        );

        $liked = true;

        /**
         * Checking user like existence
         */
        if ($this->userVideoLikeRepository->existsForVideo(
            AuthService::user(),
            $videoId
        )) {

            /**
             * Deleting user like
             */
            $this->userVideoLikeRepository->deleteForVideo(
                AuthService::user(),
                $videoId
            );

            $liked = false;
        } else {

            /**
             * Creating user like
             */
            $this->userVideoLikeRepository->store(
                AuthService::user(),
                $videoId
            );
        }

        /**
         * Getting total likes count
         */
        $userVideoLikes = $this->userVideoLikeRepository->getLikesForVideoCount(
            $videoId
        );

        /**
         * Updating user video likes count
         */
        $userVideoResponse = $this->mediaMicroservice->updateUserVideoLikes(
            $videoId,
            $userVideoLikes
        );

        return $this->respondWithSuccess(
            $this->transformItem([],
                new UserVideoLikeTransformer($liked, $userVideoResponse)
            ), trans('validations/api/general/profile/home/likeUserVideo.result.success')
        );
    }
}
