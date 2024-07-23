<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\VybeImageCollectionResponse;
use App\Microservices\Media\Responses\VybeVideoCollectionResponse;
use GuzzleHttp\Exception\GuzzleException;
use Exception;

/**
 * Trait VybeMediaTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait VybeMediaTrait
{
    /**
     * Vybe images
     */

    /**
     * @param array $vybeImagesIds
     *
     * @return VybeImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getVybeImages(
        array $vybeImagesIds
    ) : VybeImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/vybe/images/show/many', [
                'form_params' => [
                    'vybe_images_ids' => $vybeImagesIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents(),
            );

            return new VybeImageCollectionResponse(
                $responseData->vybe_images,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vybe.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $vybeImagesArray
     *
     * @return VybeImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeVybeImages(
        array $vybeImagesArray
    ) : VybeImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/vybe/images', [
                'form_params' => [
                    'vybe_images' => $vybeImagesArray
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new VybeImageCollectionResponse(
                $responseData->vybe_images,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vybe.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $vybeImagesIds
     *
     * @return VybeImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function acceptVybeImages(
        array $vybeImagesIds
    ) : VybeImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/vybe/images/accept', [
                'form_params' => [
                    'vybe_images_ids' => $vybeImagesIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new VybeImageCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vybe.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $vybeImagesIds
     *
     * @return VybeImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function declineVybeImages(
        array $vybeImagesIds
    ) : VybeImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/vybe/images/decline', [
                'form_params' => [
                    'vybe_images_ids' => $vybeImagesIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new VybeImageCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vybe.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $vybeImagesIds
     *
     * @return VybeImageCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deleteVybeImages(
        array $vybeImagesIds
    ) : VybeImageCollectionResponse
    {
        try {
            $response = $this->client->request(
                'DELETE',
                $this->apiUrl . '/vybe/images', [
                'form_params' => [
                    'vybe_images_ids' => $vybeImagesIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new VybeImageCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vybe.' . __FUNCTION__)
            );
        }
    }

    /**
     * Vybe videos
     */

    /**
     * @param array $vybeVideosIds
     *
     * @return VybeVideoCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getVybeVideos(
        array $vybeVideosIds
    ) : VybeVideoCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/vybe/videos/show/many', [
                'form_params' => [
                    'vybe_videos_ids' => $vybeVideosIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents(),
            );

            return new VybeVideoCollectionResponse(
                $responseData->vybe_videos,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vybe.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $vybeVideosArray
     *
     * @return VybeVideoCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeVybeVideos(
        array $vybeVideosArray
    ) : VybeVideoCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/vybe/videos', [
                'form_params' => [
                    'vybe_videos' => $vybeVideosArray
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new VybeVideoCollectionResponse(
                $responseData->vybe_videos,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vybe.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $vybeVideosIds
     *
     * @return VybeVideoCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function acceptVybeVideos(
        array $vybeVideosIds
    ) : VybeVideoCollectionResponse
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/vybe/videos/accept', [
                'form_params' => [
                    'vybe_videos_ids' => $vybeVideosIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new VybeVideoCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vybe.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $vybeVideosIds
     *
     * @return VybeVideoCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function declineVybeVideos(
        array $vybeVideosIds
    ) : VybeVideoCollectionResponse
    {
        try {
            $response = $this->client->request(
                'DELETE',
                $this->apiUrl . '/vybe/videos/decline', [
                'form_params' => [
                    'vybe_videos_ids' => $vybeVideosIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new VybeVideoCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vybe.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param array $vybeVideosIds
     *
     * @return VybeVideoCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deleteVybeVideos(
        array $vybeVideosIds
    ) : VybeVideoCollectionResponse
    {
        try {
            $response = $this->client->request(
                'DELETE',
                $this->apiUrl . '/vybe/videos', [
                'form_params' => [
                    'vybe_videos_ids' => $vybeVideosIds
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new VybeVideoCollectionResponse([],
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/vybe.' . __FUNCTION__)
            );
        }
    }
}