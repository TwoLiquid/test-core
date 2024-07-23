<?php

namespace App\Services\Auth;

use App\Exceptions\DatabaseException;
use App\Lists\Language\LanguageList;
use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;
use App\Repositories\Admin\AdminRepository;
use App\Repositories\User\UserRepository;
use App\Services\Auth\Interfaces\AuthServiceInterface;

/**
 * Class AuthService
 *
 * @package App\Services\Auth
 */
class AuthService implements AuthServiceInterface
{
    /**
     * @return User|null
     *
     * @throws DatabaseException
     */
    public static function user() : ?User
    {
        /** @var UserRepository $userRepository */
        $userRepository = app(UserRepository::class);

        if (getIsAdminFromJWT() === false) {

            /** @var User $user */
            $user = $userRepository->findByAuthId(
                getAuthIdFromJWT()
            );

            return $user;
        }

        return null;
    }

    /**
     * @return Admin|null
     *
     * @throws DatabaseException
     */
    public static function admin() : ?Admin
    {
        /** @var AdminRepository $adminRepository */
        $adminRepository = app(AdminRepository::class);

        if (getIsAdminFromJWT() === true) {

            /** @var Admin $admin */
            $admin = $adminRepository->findByAuthId(
                getAuthIdFromJWT()
            );

            return $admin;
        }

        return null;
    }

    /**
     * @return string
     *
     * @throws DatabaseException
     */
    public static function getLocalizationHeader() : string
    {
        if (self::user()) {
            return self::user()
                ->getLanguage()
                ->iso;
        } elseif (request()->hasHeader('X-Localization')) {
            return request()->hasHeader('X-Localization');
        } else {
            return LanguageList::getEnglish()->iso;
        }
    }
}
