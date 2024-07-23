<?php

namespace App\Settings\User\Interfaces;

/**
 * Interface MaximumNumberOfUsersSettingInterface
 *
 * @package App\Settings\User\Interfaces
 */
interface MaximumNumberOfUsersSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @param bool $default
     *
     * @return int|null
     */
    public function getGroupVybes(
        bool $default
    ) : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @param bool $default
     *
     * @return int|null
     */
    public function getEvents(
        bool $default
    ) : ?int;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int|null $value
     * @param bool|null $active
     */
    public function setGroupVybes(
        ?int $value,
        ?bool $active
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int|null $value
     * @param bool|null $active
     */
    public function setEvents(
        ?int $value,
        ?bool $active
    ) : void;
}