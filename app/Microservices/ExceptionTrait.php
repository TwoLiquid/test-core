<?php

namespace App\Microservices;

use App\Exceptions\MicroserviceException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Exception;

/**
 * Trait ExceptionTrait
 *
 * @package App\Microservices
 */
trait ExceptionTrait
{
    /**
     * @param Exception $exception
     * @param string $humanReadableMessage
     *
     * @return MicroserviceException
     */
    public function executeException(
        Exception $exception,
        string $humanReadableMessage
    ) : MicroserviceException
    {
        /**
         * Connection error exception | Example: Bad host or unavailable to connect
         */
        if ($exception instanceof ConnectException) {

            /**
             * Execute exception as connection error
             */
            return new MicroserviceException(
                self::APPEARANCE,
                trans('exceptions/microservice/auth.connection'),
                $exception->getMessage(),
                null,
                503
            );
        }

        /**
         * Guzzle client error | Example: Microservice auth error (401), Unprocessable entity (422)
         */
        if ($exception instanceof ClientException) {

            /**
             * Checking auth error
             */
            if ($exception->getCode() == 401) {

                $message = $exception->getMessage();

                /**
                 * Getting response data
                 */
                $responseData = json_decode(
                    $exception->getResponse()
                        ->getBody()
                        ->getContents()
                );

                if (isset($responseData->errors)) {
                    if (is_array($responseData->errors)) {
                        if (isset($responseData->errors[0]->message)) {
                            $message = $responseData->errors[0]->message;
                        }
                    } else {
                        if (isset($responseData->errors->message)) {
                            $message = $responseData->errors->message;
                        }
                    }
                }

                /**
                 * Execute exception as validation error
                 */
                return new MicroserviceException(
                    self::APPEARANCE,
                    $message,
                    trans('exceptions/microservice/auth.authorization'),
                    null,
                    401
                );

                /**
                 * Checking unprocessable entity error
                 */
            } elseif ($exception->getCode() == 422 || $exception->getCode() == 400) {

                /**
                 * Getting response data
                 */
                $responseData = json_decode(
                    $exception->getResponse()
                        ->getBody()
                        ->getContents()
                );

                /**
                 * Setting validation errors
                 */
                $validationErrors = null;

                if (isset($responseData->errors)) {
                    $validationErrors = get_object_vars($responseData->errors);
                }

                /**
                 * Execute base exception as validation error
                 */
                return new MicroserviceException(
                    self::APPEARANCE,
                    trans('exceptions/microservice/auth.validation'),
                    $exception->getMessage(),
                    $validationErrors,
                    $exception->getCode()
                );
            }
        }

        /**
         * Service error | Example: Microservice error (500)
         */
        if ($exception instanceof ServerException) {

            /**
             * Getting response data
             */
            $responseData = json_decode(
                $exception->getResponse()
                    ->getBody()
                    ->getContents()
            );

            /**
             * Setting server errors
             */
            if (isset($responseData->error)) {

                /**
                 * Execute exception as server error
                 */
                return new MicroserviceException(
                    self::APPEARANCE,
                    $responseData->error->message,
                    $exception->getMessage(),
                    null,
                    500
                );
            }
        }

        /**
         * Getting response data
         */
        $responseData = json_decode(
            $exception->getResponse()
                ->getBody()
                ->getContents()
        );

        /**
         * Setting errors
         */
        if (isset($responseData->errors)) {
            return new MicroserviceException(
                self::APPEARANCE,
                $responseData->errors->message,
                $exception->getMessage(),
                null,
                $exception->getCode()
            );
        }

        return new MicroserviceException(
            self::APPEARANCE,
            $humanReadableMessage,
            $exception->getMessage(),
            null,
            $exception->getCode()
        );
    }
}