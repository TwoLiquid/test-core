<?php

namespace App\Services\Bugsnag\Interfaces;

/**
 * Interface BugsnagServiceInterface
 *
 * @package App\Services\Bugsnag\Interfaces
 */
interface BugsnagServiceInterface
{
    /**
     * This method provides getting data
     *
     * @return bool
     */
    public function checkApiIntegration() : bool;
}
