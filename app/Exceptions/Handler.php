<?php

namespace App\Exceptions;

use App\Support\Response\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    /**
     * Handler constructor
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register() : void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception
     *
     * @param Throwable $e
     *
     * @throws Throwable
     */
    public function report(Throwable $e) : void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     *
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e) : JsonResponse
    {
        return $this->handleException($e);
    }

    /**
     * @param Throwable $exception
     *
     * @return JsonResponse
     */
    public function handleException(
        Throwable $exception
    ) : JsonResponse
    {
        /**
         * Handling base exception
         */
        if ($exception instanceof BaseException) {
            return $this->respondRawError(
                $exception->getHumanReadableMessage(),
                $exception->getErrorCode()
            );
        }

        /**
         * Handling database exception
         */
        if ($exception instanceof DatabaseException) {
            return $this->respondRawError(
                $exception->getHumanReadableMessage(),
                $exception->getErrorCode()
            );
        }

        /**
         * Handling microservice exception
         */
        if ($exception instanceof MicroserviceException) {
            if ($exception->getValidationErrors()) {
                return $this->respondRawError(
                    $exception->getValidationErrors(),
                    $exception->getCode()
                );
            } else {
                return $this->respondRawError(
                    $exception->getHumanReadableMessage(),
                    $exception->getErrorCode()
                );
            }
        }

        /**
         * Handling database exception
         */
        if ($exception instanceof ValidationException) {
            return $this->respondRawError([
                $exception->getErrorKey() => [
                    $exception->getErrorMessage()
                ]
            ], $exception->getCode());
        }

        /**
         * Handling authentication exception
         */
        if ($exception instanceof AuthenticationException) {
            return $this->respondRawError(
                $exception->getMessage(),
                401
            );
        }

        /**
         * Handling http exception
         */
        if ($exception instanceof HttpClientException) {
            return $this->respondRawError(
                $exception->getMessage(),
                $exception->getCode()
            );
        }

        return $this->respondWithError($exception);
    }
}
