<?php

namespace App\Support\Response;

use App\Exceptions\MicroserviceException;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Mccarlosen\LaravelMpdf\LaravelMpdf;
use Mpdf\MpdfException;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Exception;

/**
 * Trait ApiResponseTrait
 *
 * @package App\Support\Response
 */
trait ApiResponseTrait
{
    /**
     * @var int
     */
    private int $statusCode = 200;

    /**
     * @var array|null
     */
    private ?array $pagination = null;

    /**
     * @var array|null
     */
    private ?array $backgroundErrors = null;

    /**
     * @param Exception $exception
     */
    protected function addBackgroundError(
        Exception $exception
    ) : void
    {
        /**
         * Checking is it a microservice exception
         */
        if ($exception instanceof MicroserviceException) {

            /**
             * Checking it has validation errors
             */
            if ($exception->getValidationErrors() && $exception->getCode() == 422) {

                /**
                 * @var string $key
                 * @var string $validationError
                 */
                foreach ($exception->getValidationErrors() as $key => $validationError) {
                    $this->backgroundErrors[$exception->getAppearance()][] = [
                        'key'     => $key,
                        'message' => $validationError[0] ?? $exception->getHumanReadableMessage()
                    ];
                }
            } else {
                Bugsnag::notifyException(
                    $exception
                );

                $this->backgroundErrors[$exception->getAppearance()][] = [
                    'message' => $exception->getHumanReadableMessage()
                ];
            }
        } else {
            if ($exception->getCode() != 422) {
                Bugsnag::notifyException(
                    $exception
                );
            }

            $this->backgroundErrors['exceptions'][] = [
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    protected function setStatusCode(
        int $statusCode
    ) : static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param LengthAwarePaginator $paginator
     *
     * @return $this
     */
    protected function setPagination(
        LengthAwarePaginator $paginator
    ) : static
    {
        $this->pagination = [
            'page'      => $paginator->currentPage(),
            'by'        => $paginator->perPage(),
            'total'     => $paginator->total(),
            'lastPage'  => $paginator->lastPage()
        ];

        return $this;
    }

    /**
     * @return int
     */
    protected function getStatusCode() : int
    {
        return $this->statusCode;
    }

    /**
     * @param array $data
     * @param array $headers
     *
     * @return JsonResponse
     */
    private function respond(
        array $data = [],
        array $headers = []
    ) : JsonResponse
    {
        return response()->json(
            $data,
            $this->getStatusCode(),
            $headers,
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param array|null $data
     * @param string|null $message
     *
     * @return JsonResponse
     */
    protected function respondWithSuccess(
        array $data = null,
        string $message = null
    ) : JsonResponse {
        $responseBody = $data !== null ? $data : [];
        if ($message !== null) {
            $responseBody['message'] = $message;
        }
        if ($this->pagination !== null) {
            $responseBody['pagination'] = $this->pagination;
            $this->pagination = null;
        }
        if (empty($responseBody)) {
            $responseBody = new stdClass;
        }
        if ($this->backgroundErrors) {
            $responseBody['background_errors'] = $this->backgroundErrors;
        }

        return $this->respond($responseBody);
    }

    /**
     * @param string|null $message
     *
     * @return JsonResponse
     */
    protected function respondWithError(
        string $message = null
    ) : JsonResponse
    {
        return $this->respondRawError($message);
    }

    /**
     * @param string|null $message
     *
     * @return JsonResponse
     */
    protected function respondNotFound(
        string $message = null
    ) : JsonResponse
    {
        return $this->respondRawError($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * @param string|null $message
     *
     * @return JsonResponse
     */
    protected function respondForbidden(
        string $message = null
    ) : JsonResponse
    {
        return $this->respondRawError($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * @param mixed $data
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    private function respondRawError(
        mixed $data,
        int $statusCode = 400
    ) : JsonResponse
    {
        if (is_string($data)) {
            $responseBody = [
                'errors' => []
            ];

            $responseBody['errors']['message'] = $data;

            if (empty($responseBody['errors'])) {
                $responseBody['errors'] = new stdClass;
            }
        } elseif (is_array($data)) {
            $responseBody = [
                'errors' => $data
            ];
        } else {
            $responseBody = [
                'errors' => [
                    'message' => 'Unknown error.'
                ]
            ];
        }

        if ($this->backgroundErrors) {
            $responseBody['background_errors'] = $this->backgroundErrors;
        }

        return $this->setStatusCode($statusCode)
            ->respond($responseBody);
    }

    /**
     * @param array $data
     * @param string|null $message
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    protected function respondWithErrors(
        array $data,
        string $message = null,
        int $statusCode = 400
    ) : JsonResponse
    {
        $responseBody = [
            'errors'  => $data
        ];

        if ($message) {
            $responseBody['message'] = $message;
        }

        return $this->setStatusCode($statusCode)
            ->respond($responseBody);
    }

    /**
     * @param array $messages
     *
     * @return JsonResponse
     */
    protected function respondWithValidationError(
        array $messages
    ) : JsonResponse
    {
        return $this->respondRawError(
            $messages,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * @param array $data
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function respondWithValidationErrors(
        array $data,
        string $message
    ) : JsonResponse
    {
        $responseBody = [
            'errors'  => $data
        ];

        if ($message) {
            $responseBody['message'] = $message;
        }

        return $this->respondRawError(
            $responseBody,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * @param string|null $message
     *
     * @return JsonResponse
     */
    protected function respondWithAuthorizationError(
        string $message = null
    ) : JsonResponse
    {
        $message = ($message === null ? 'Authorization error. ' : $message);

        return $this->respondRawError($message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param LaravelMpdf $laravelMpdf
     * @param string $filename
     *
     * @return Response
     *
     * @throws MpdfException
     */
    protected function respondWithMpdfDownload(
        LaravelMpdf $laravelMpdf,
        string $filename
    ) : Response
    {
        header('Access-Control-Allow-Origin: *');

        return $laravelMpdf->stream(
            $filename
        );
    }
}
