<?php

namespace App\Http\Middleware;

use App\Exceptions\DatabaseException;
use App\Services\Auth\AuthService;
use Closure;
use Illuminate\Http\Request;

/**
 * Class Localization
 *
 * @package App\Http\Middleware
 */
class Localization
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     *
     * @throws DatabaseException
     */
    public function handle(Request $request, Closure $next) : mixed
    {
        if ($request->hasHeader('X-Localization')) {
            app()->setLocale(
                $request->header('X-Localization')
            );
        }

        if (AuthService::user()) {
            app()->setLocale(
                AuthService::user()->getLanguage()
                    ->iso
            );
        }

        return $next($request);
    }
}
