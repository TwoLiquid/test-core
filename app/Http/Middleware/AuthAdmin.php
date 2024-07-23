<?php

namespace App\Http\Middleware;

use App\Exceptions\DatabaseException;
use App\Services\Auth\AuthService;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

/**
 * Class AuthAdmin
 *
 * @package App\Http\Middleware
 */
class AuthAdmin
{
    /**
     * Handle an incoming request
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     *
     * @throws AuthenticationException
     * @throws DatabaseException
     */
    public function handle(
        Request $request,
        Closure $next
    ) : mixed
    {
        /**
         * Checking admin is authenticated
         */
        if (!AuthService::admin()) {
            throw new AuthenticationException(
                trans('middlewares/authAdmin.access')
            );
        }

        return $next($request);
    }
}
