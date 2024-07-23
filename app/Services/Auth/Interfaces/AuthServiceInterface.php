<?php

namespace App\Services\Auth\Interfaces;

use App\Models\MySql\Admin\Admin;
use App\Models\MySql\User\User;

/**
 * Interface AuthServiceInterface
 *
 * @package App\Services\Auth\Interfaces
 */
interface AuthServiceInterface
{
    /**
     * This method provides getting data
     *
     * @return User|null
     */
    public static function user() : ?User;

    /**
     * This method provides getting data
     *
     * @return Admin|null
     */
    public static function admin() : ?Admin;

    /**
     * This method provides getting data
     *
     * @return string
     */
    public static function getLocalizationHeader() : string;
}