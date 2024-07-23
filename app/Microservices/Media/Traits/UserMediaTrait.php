<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\BaseResponse;
use App\Microservices\Media\Responses\UserAvatarResponse;
use App\Microservices\Media\Responses\UserBackgroundResponse;
use App\Microservices\Media\Responses\UserIdVerificationImageResponse;
use App\Microservices\Media\Responses\UserImageCollectionResponse;
use App\Microservices\Media\Responses\UserImageResponse;
use App\Microservices\Media\Responses\UserVideoCollectionResponse;
use App\Microservices\Media\Responses\UserVideoResponse;
use App\Microservices\Media\Responses\UserVoiceSampleResponse;
use App\Models\MongoDb\User\Request\IdVerification\UserIdVerificationRequest;
use App\Models\MongoDb\User\Request\Profile\UserProfileRequest;
use App\Models\MySql\User\User;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

/**
 * Trait UserMediaMicroserviceTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait UserMediaTrait
{
    /**
     * General user media
     */

    /**
     * @param User $user
     * @param array|null $avatarsIds
     * @param array|null $voiceSamplesIds
     * @param array|null $backgroundsIds
     * @param array|null $imagesIds
     * @param array|null $videosIds
     * @param array|null $vybeImagesIds
     * @param array|null $vybeVideosIds
     * @param array|null $activitiesIds
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getForUser(
        User $user,
        ?array $avatarsIds = null,
        ?array $voiceSamplesIds = null,
        ?array $backgroundsIds = null,
        ?array $imagesIds = null,
        ?array $videosIds = null,
        ?array $vybeImagesIds = null,
        ?array $vybeVideosIds = null,
        ?array $activitiesIds = null
    ) : array
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/user/' . $user->auth_id, [
                'form_params' => [
                    'avatars_ids'       => $avatarsIds,
                    'voice_samples_ids' => $voiceSamplesIds,
                    'backgrounds_ids'   => $backgroundsIds,
                    'images_ids'        => $imagesIds,
                    'videos_ids'        => $videosIds,
                    'vybe_images_ids'   => $vybeImagesIds,
                    'vybe_videos_ids'   => $vybeVideosIds,
                    'activities_ids'    => $activitiesIds
                ]
            ]);

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array|null $avatarsIds
     * @param array|null $voiceSamplesIds
     * @param array|null $backgroundsIds
     * @param array|null $imagesIds
     * @param array|null $videosIds
     * @param array|null $vybeImagesIds
     * @param array|null $vybeVideosIds
     * @param array|null $activitiesIds
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getForUsers(
        ?array $avatarsIds = null,
        ?array $voiceSamplesIds = null,
        ?array $backgroundsIds = null,
        ?array $imagesIds = null,
        ?array $videosIds = null,
        ?array $vybeImagesIds = null,
        ?array $vybeVideosIds = null,
        ?array $activitiesIds = null
    ) : array
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/users', [
                'form_params' => [
                    'avatars_ids'       => $avatarsIds,
                    'voice_samples_ids' => $voiceSamplesIds,
                    'backgrounds_ids'   => $backgroundsIds,
                    'images_ids'        => $imagesIds,
                    'videos_ids'        => $videosIds,
                    'vybe_images_ids'   => $vybeImagesIds,
                    'vybe_videos_ids'   => $vybeVideosIds,
                    'activities_ids'    => $activitiesIds
                ]
            ]);

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getForRequest(
        UserProfileRequest $userProfileRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/user/request/' . $userProfileRequest->_id
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * User avatars
     */

    /**
     * @param User $user
     * @param UserProfileRequest|null $userProfileRequest
     * @param string $content
     * @param string $mime
     * @param string $extension
     *
     * @return UserAvatarResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeUserAvatar(
        User $user,
        ?UserProfileRequest $userProfileRequest,
        string $content,
        string $mime,
        string $extension
    ) : UserAvatarResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/user/' . $user->auth_id . '/avatar', [
                'form_params' => [
                    'request_id' => $userProfileRequest?->_id,
                    'content'    => $content,
                    'mime'       => $mime,
                    'extension'  => $extension
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserAvatarResponse(
                $responseData->user_avatar,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function acceptUserAvatar(
        UserProfileRequest $userProfileRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/avatar/request/' . $userProfileRequest->_id . '/accept'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function declineUserAvatar(
        UserProfileRequest $userProfileRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/avatar/request/' . $userProfileRequest->_id . '/decline'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * User backgrounds
     */

    /**
     * @param User $user
     * @param UserProfileRequest|null $userProfileRequest
     * @param string $content
     * @param string $mime
     * @param string $extension
     *
     * @return UserBackgroundResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeUserBackground(
        User $user,
        ?UserProfileRequest $userProfileRequest,
        string $content,
        string $mime,
        string $extension
    ) : UserBackgroundResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/user/' . $user->auth_id . '/background', [
                'form_params' => [
                    'request_id' => $userProfileRequest?->_id,
                    'content'    => $content,
                    'mime'       => $mime,
                    'extension'  => $extension
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserBackgroundResponse(
                $responseData->user_background,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function acceptUserBackground(
        UserProfileRequest $userProfileRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/background/request/' . $userProfileRequest->_id . '/accept'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function declineUserBackground(
        UserProfileRequest $userProfileRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/background/request/' . $userProfileRequest->_id . '/decline'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserProfileRequest|null $userProfileRequest
     * @param string $content
     * @param string $mime
     * @param string $extension
     *
     * @return UserVoiceSampleResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeUserVoiceSample(
        User $user,
        ?UserProfileRequest $userProfileRequest,
        string $content,
        string $mime,
        string $extension
    ) : UserVoiceSampleResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/user/' . $user->auth_id . '/voice/sample', [
                'form_params' => [
                    'request_id' => $userProfileRequest?->_id,
                    'content'    => $content,
                    'mime'       => $mime,
                    'extension'  => $extension
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserVoiceSampleResponse(
                $responseData->user_voice_sample,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function acceptUserVoiceSample(
        UserProfileRequest $userProfileRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/voice/sample/request/' . $userProfileRequest->_id . '/accept'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function declineUserVoiceSample(
        UserProfileRequest $userProfileRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/voice/sample/request/' . $userProfileRequest->_id . '/decline'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * User images
     */

    /**
     * @param User $user
     * @param int $imageId
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function existsImageForUser(
        User $user,
        int $imageId
    ) : BaseResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/user/' . $user->auth_id . '/image/' . $imageId . '/exists'
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new BaseResponse(
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param array $imagesArray
     *
     * @return UserImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeUserImages(
        User $user,
        array $imagesArray
    ) : UserImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/user/' . $user->auth_id . '/images', [
                'form_params' => [
                    'user_images' => $imagesArray
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserImageCollectionResponse(
                $responseData->user_images,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function acceptUserImages(
        UserProfileRequest $userProfileRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/images/request/' . $userProfileRequest->_id . '/accept'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function declineUserImages(
        UserProfileRequest $userProfileRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/images/request/' . $userProfileRequest->_id . '/decline'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param int $imageId
     * @param int $likes
     *
     * @return UserImageResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function updateUserImageLikes(
        int $imageId,
        int $likes
    ) : UserImageResponse
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/image/' . $imageId . '/likes', [
                'form_params' => [
                    'likes' => $likes
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserImageResponse(
                $responseData->user_image,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * User videos
     */

    /**
     * @param User $user
     * @param int $videoId
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function existsVideoForUser(
        User $user,
        int $videoId
    ) : BaseResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/user/' . $user->auth_id . '/video/' . $videoId . '/exists'
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new BaseResponse(
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param array $videosArray
     *
     * @return UserVideoCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeUserVideos(
        User $user,
        array $videosArray
    ) : UserVideoCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/user/' . $user->auth_id . '/videos', [
                'form_params' => [
                    'user_videos' => $videosArray
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserVideoCollectionResponse(
                $responseData->user_videos,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function acceptUserVideos(
        UserProfileRequest $userProfileRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/videos/request/' . $userProfileRequest->_id . '/accept'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserProfileRequest $userProfileRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function declineUserVideos(
        UserProfileRequest $userProfileRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/videos/request/' . $userProfileRequest->_id . '/decline');

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param int $videoId
     * @param int $likes
     *
     * @return UserVideoResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function updateUserVideoLikes(
        int $videoId,
        int $likes
    ) : UserVideoResponse
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/video/' . $videoId . '/likes', [
                'form_params' => [
                    'likes' => $likes
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserVideoResponse(
                $responseData->user_video,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * User id verification images
     */

    /**
     * @param int $authId
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getUserIdVerificationImages(
        int $authId
    ) : array
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/user/' . $authId . '/id/verification/images'
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getUserIdVerificationImagesForRequest(
        UserIdVerificationRequest $userIdVerificationRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/user/id/verification/images/request/' . $userIdVerificationRequest->_id
            );

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param UserIdVerificationRequest $userIdVerificationRequest
     * @param string $content
     * @param string $mime
     * @param string $extension
     *
     * @return UserIdVerificationImageResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeUserIdVerificationImage(
        User $user,
        UserIdVerificationRequest $userIdVerificationRequest,
        string $content,
        string $mime,
        string $extension
    ) : UserIdVerificationImageResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/user/' . $user->auth_id . '/id/verification/image', [
                'form_params' => [
                    'request_id' => $userIdVerificationRequest->_id,
                    'content'    => $content,
                    'mime'       => $mime,
                    'extension'  => $extension
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserIdVerificationImageResponse(
                $responseData->user_id_verification_image,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param UserIdVerificationRequest $userIdVerificationRequest
     *
     * @return array
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function declineUserIdVerificationImage(
        UserIdVerificationRequest $userIdVerificationRequest
    ) : array
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/id/verification/images/request/' . $userIdVerificationRequest->_id . '/decline');

            return json_decode(
                $response->getBody()->getContents(),
                true
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/user.' . __FUNCTION__)
            );
        }
    }
}
