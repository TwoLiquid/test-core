<?php

namespace App\Settings\User\Interfaces;

/**
 * Interface MaximumNumberOfVybesSettingInterface
 *
 * @package App\Settings\User\Interfaces
 */
interface MaximumNumberOfVybesSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getSoloVybes() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getGroupVybes() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getEvents() : ?int;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     * @param bool|null $active
     */
    public function setSoloVybes(
        int $value,
        ?bool $active
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     * @param bool|null $active
     */
    public function setGroupVybes(
        int $value,
        ?bool $active
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     * @param bool|null $active
     */
    public function setEvents(
        int $value,
        ?bool $active
    ) : void;
}