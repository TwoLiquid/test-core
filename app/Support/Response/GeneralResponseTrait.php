<?php

namespace App\Support\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Request;
use URL;

/**
 * Trait GeneralResponseTrait
 *
 * @package App\Support\Response
 */
trait GeneralResponseTrait
{
    /**
     * @param string $message
     * @param string|null $to
     * @param array $params
     *
     * @return JsonResponse|RedirectResponse
     */
    protected function success(
        string $message = '',
        string $to = null,
        array $params = []
    ) : JsonResponse|RedirectResponse
    {
        return $this->successfulResponse($message, $to, $params);
    }

    /**
     * @param string $message
     * @param string|null $to
     * @param array $params
     *
     * @return JsonResponse|RedirectResponse
     */
    protected function warning(
        string $message = '',
        string $to = null,
        array $params = []
    ) : JsonResponse|RedirectResponse
    {
        return $this->successfulResponse($message, $to, $params, 'warning');
    }

    /**
     * @param string $message
     * @param string|null $to
     * @param array $params
     * @param string $dataKey
     *
     * @return JsonResponse|RedirectResponse
     */
    private function successfulResponse(
        string $message = '',
        string $to = null,
        array $params = [],
        string $dataKey = 'success'
    ) : JsonResponse|RedirectResponse
    {
        if (Request::ajax() || Request::wantsJson()) {
            return response()->json([
                'message' => $message,
                'data'    => $params
            ]);
        }
        $redirect = redirect()->to($to === null ? URL::previous() : $to);
        if ($message) {
            $redirect = $redirect->with($dataKey, $message);
        }
        foreach ($params as $key => $value) {
            $redirect = $redirect->with($key, $value);
        }

        return $redirect;
    }

    /**
     * @param array $messages
     * @param string|null $to
     * @param array $params
     * @param array|null $exceptInput
     * @param int $statusCode
     *
     * @return JsonResponse|RedirectResponse
     */
    protected function error(
        array $messages = [],
        string $to = null,
        array $params = [],
        array $exceptInput = null,
        int $statusCode = 400
    ) : JsonResponse|RedirectResponse
    {
        if ($exceptInput === null) {
            $exceptInput = ['password', 'password_confirmation'];
        }
        if (Request::ajax() || Request::wantsJson()) {
            return response()->json([
                'message' => array_first($messages),
                'data'    => $params
            ], $statusCode);
        }
        $redirect = redirect()->to($to === null ? URL::previous() : $to)
                ->withErrors($messages)
                ->withInput(Request::except($exceptInput));
        foreach ($params as $key => $value) {
            $redirect = $redirect->with($key, $value);
        }

        return $redirect;
    }
}
