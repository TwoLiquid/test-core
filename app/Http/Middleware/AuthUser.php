<?php

namespace App\Http\Middleware;

use App\Exceptions\DatabaseException;
use App\Services\Auth\AuthService;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

/**
 * Class AuthUser
 *
 * @package App\Http\Middleware
 */
class AuthUser
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
         * Checking user is authenticated
         */
        if (!AuthService::user()) {
            throw new AuthenticationException(
                trans('middlewares/authUser.access')
            );
        }

        return $next($request);
    }
}
