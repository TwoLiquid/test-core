<?php

namespace App\Http\Middleware;

use App\Microservices\Auth\AuthMicroservice;
use Closure;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Exception;

/**
 * Class Gateway
 *
 * @package App\Http\Middleware
 */
class Gateway
{
    /**
     * @var AuthMicroservice
     */
    protected AuthMicroservice $authMicroservice;

    /**
     * Gateway constructor
     */
    public function __construct()
    {
        /** @var AuthMicroservice authMicroservice */
        $this->authMicroservice = new AuthMicroservice();
    }

    /**
     * Handle an incoming request
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     *
     * @throws AuthenticationException
     * @throws GuzzleException
     */
    public function handle(
        Request $request,
        Closure $next
    ) : mixed
    {
        if (request()->bearerToken() === null) {
            throw new AuthenticationException();
        }

        try {

            /**
             * Getting auth gateway token check
             */
            $this->authMicroservice->checkToken();
        } catch (Exception $exception) {
            if ($exception->getCode() == 401) {
                throw new AuthenticationException(
                    $exception->getMessage()
                );
            }
        }

        return $next($request);
    }
}
