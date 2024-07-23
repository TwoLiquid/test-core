<?php

namespace App\Microservices\Auth\Traits;

use App\Exceptions\MicroserviceException;
use App\Microservices\Auth\Responses\BaseResponse;
use App\Microservices\Auth\Responses\UserResponse;
use App\Models\MySql\User\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Trait AuthTrait
 *
 * @package App\Microservices\Auth\Traits
 */
trait AuthTrait
{
    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @return UserResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function registerUser(
        string $username,
        string $email,
        string $password
    ) : UserResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/auth/users/register', [
                    'form_params' => [
                        'username' => $username,
                        'email'    => $email,
                        'password' => $password
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserResponse(
                $responseData->user,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return UserResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function registerAdmin(
        string $email,
        string $password
    ) : UserResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/auth/admins/register', [
                    'form_params' => [
                        'email'    => $email,
                        'password' => $password
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserResponse(
                $responseData->user,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $login
     * @param string $password
     *
     * @return UserResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function loginUser(
        string $login,
        string $password
    ) : UserResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/auth/users/login', [
                    'form_params' => [
                        'login'    => $login,
                        'password' => $password
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserResponse(
                $responseData->user,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return UserResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function loginAdmin(
        string $email,
        string $password
    ) : UserResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/auth/admins/login', [
                    'form_params' => [
                        'email'    => $email,
                        'password' => $password
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserResponse(
                $responseData->user,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return UserResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function retrieveAdmin(
        string $email,
        string $password
    ) : UserResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/auth/admins/retrieve', [
                    'form_params' => [
                        'email'    => $email,
                        'password' => $password
                    ]
                ]
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserResponse(
                $responseData->user,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function logout() : BaseResponse
    {
        try {
            $this->headers += [
                'Authorization' => 'Bearer ' . request()->bearerToken()
            ];

            $this->client = new Client([
                'headers' => $this->headers
            ]);

            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/auth/logout'
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
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function test() : BaseResponse
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/auth/test'
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
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @return UserResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function checkToken() : UserResponse
    {
        try {
            $this->headers += [
                'Authorization' => 'Bearer ' . request()->bearerToken()
            ];

            $this->client = new Client([
                'headers' => $this->headers
            ]);

            $response = $this->client->request(
                'GET',
                $this->apiUrl . '/auth/check/token'
            );

            $responseData = json_decode(
                $response->getBody()->getContents()
            );

            return new UserResponse(
                $responseData->user,
                $responseData->message
            );
        } catch (Exception $exception) {
            throw $this->executeException(
                $exception,
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $username
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function checkUsername(
        string $username
    ) : BaseResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/auth/check/username', [
                    'form_params' => [
                        'username' => $username
                    ]
                ]
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
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $email
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function checkEmail(
        string $email
    ) : BaseResponse
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/auth/check/email', [
                    'form_params' => [
                        'email' => $email
                    ]
                ]
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
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param User $user
     * @param string $username
     * @param string $email
     * @param string|null $password
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function updateUserByAdmin(
        User $user,
        string $username,
        string $email,
        ?string $password
    ) : BaseResponse
    {
        try {
            $this->headers += [
                'Authorization' => 'Bearer ' . request()->bearerToken()
            ];

            $this->client = new Client([
                'headers' => $this->headers
            ]);

            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/' . $user->auth_id . '/by/admin', [
                    'form_params' => [
                        'username' => $username,
                        'email'    => $email,
                        'password' => $password
                    ]
                ]
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
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function updatePassword(
        string $email,
        string $password
    ) : BaseResponse
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/password/reset', [
                    'form_params' => [
                        'email'    => $email,
                        'password' => $password
                    ]
                ]
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
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $email
     * @param string $username
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function updateUsername(
        string $email,
        string $username
    ) : BaseResponse
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/username', [
                    'form_params' => [
                        'email'    => $email,
                        'username' => $username
                    ]
                ]
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
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $email
     * @param string $newEmail
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function updateAdminEmail(
        string $email,
        string $newEmail
    ) : BaseResponse
    {
        try {
            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/auth/admins/email', [
                    'form_params' => [
                        'email'     => $email,
                        'new_email' => $newEmail
                    ]
                ]
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
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $password
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function checkPassword(
        string $password
    ) : BaseResponse
    {
        try {
            $this->headers += [
                'Authorization' => 'Bearer ' . request()->bearerToken()
            ];

            $this->client = new Client([
                'headers' => $this->headers
            ]);

            $response = $this->client->request(
                'POST',
                $this->apiUrl . '/user/password/check', [
                    'form_params' => [
                        'password' => $password
                    ]
                ]
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
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $email
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function updateAccountEmail(
        string $email
    ) : BaseResponse
    {
        try {
            $this->headers += [
                'Authorization' => 'Bearer ' . request()->bearerToken()
            ];

            $this->client = new Client([
                'headers' => $this->headers
            ]);

            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/account/email', [
                    'form_params' => [
                        'email' => $email
                    ]
                ]
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
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }

    /**
     * @param string $password
     *
     * @return BaseResponse
     *
     * @throws GuzzleException
     * @throws MicroserviceException
     */
    public function updateAccountPassword(
        string $password
    ) : BaseResponse
    {
        try {
            $this->headers += [
                'Authorization' => 'Bearer ' . request()->bearerToken()
            ];

            $this->client = new Client([
                'headers' => $this->headers
            ]);

            $response = $this->client->request(
                'PATCH',
                $this->apiUrl . '/user/account/password', [
                    'form_params' => [
                        'password' => $password
                    ]
                ]
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
                trans('exceptions/microservice/auth/auth.' . __FUNCTION__)
            );
        }
    }
}
