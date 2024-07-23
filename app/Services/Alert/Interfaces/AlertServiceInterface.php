<?php

namespace App\Services\Alert\Interfaces;

use App\Models\MySql\Alert\AlertProfanityFilter;
use App\Models\MySql\User\User;

/**
 * Interface AlertServiceInterface
 *
 * @package App\Services\Alert\Interfaces
 */
interface AlertServiceInterface
{
    /**
     * This method provides creating default rows
     * by related entity repositories
     *
     * @param User $user
     */
    public function createDefaultsToUser(
        User $user
    ) : void;

    /**
     * This method provides updating rows
     * by related entity repositories
     *
     * @param AlertProfanityFilter $alertProfanityFilter
     * @param array $words
     */
    public function updateProfanityWords(
        AlertProfanityFilter $alertProfanityFilter,
        array $words
    ) : void;

    /**
     * This method provides validating data
     *
     * @param array $images
     *
     * @return bool
     */
    public function validateImages(
        array $images
    ) : bool;

    /**
     * This method provides validating data
     *
     * @param array $sounds
     *
     * @return bool
     */
    public function validateSounds(
        array $sounds
    ) : bool;
}