<?php

namespace App\Microservices\Media\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Media\Responses\AdminAvatarCollectionResponse;
use App\Microservices\Media\Responses\AdminAvatarResponse;
use App\Microservices\Media\Responses\BaseResponse;
use App\Models\MySql\Admin\Admin;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Trait AdminMediaTrait
 *
 * @package App\Microservices\Media\Traits
 */
trait AdminMediaTrait
{
    /**
     * @param array $authIds
     *
     * @return AdminAvatarCollectionResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function getAdminsAvatars(
        array $authIds
    ) : AdminAvatarCollectionResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/admins/avatars',[
                    'form_params' => [
                        'auth_ids' => $authIds
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents(),
            );

            return new AdminAvatarCollectionResponse(
                $responseData->admin_avatars,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/admin.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Admin $admin
     * @param string $content
     * @param string $mime
     * @param string $extension
     *
     * @return AdminAvatarResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function storeAdminAvatar(
        Admin $admin,
        string $content,
        string $mime,
        string $extension
    ) : AdminAvatarResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/admin/' . $admin->auth_id . '/avatar', [
                'form_params' => [
                    'content'   => $content,
                    'mime'      => $mime,
                    'extension' => $extension
                ]
            ]);

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new AdminAvatarResponse(
                $responseData->admin_avatar,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/media/admin.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param Admin $admin
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function deleteAdminAvatars(
        Admin $admin
    ) : BaseResponse
    {
        try {
            $response = $this->client->request(
                'DELETE',
                $this->apiUrl . '/admin/' . $admin->auth_id . '/avatars'
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
                trans('exceptions/microservice/media/admin.' . __FUNCTION__)
            );
        }
    }
}
