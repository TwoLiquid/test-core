<?php

namespace App\Settings\User\Interfaces;

/**
 * Interface DaysThatVybesCanBeOrderedSettingInterface
 *
 * @package App\Settings\User\Interfaces
 */
interface DaysThatVybesCanBeOrderedSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @param bool $default
     *
     * @return int|null
     */
    public function getSoloVybesMinimumDays(
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
    public function getSoloVybesMaximumDays(
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
    public function getGroupVybesMinimumDays(
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
    public function getGroupVybesMaximumDays(
        bool $default
    ) : ?int;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int|null $value
     * @param bool|null $active
     */
    public function setSoloVybesMinimumDays(
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
    public function setSoloVybesMaximumDays(
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
    public function setGroupVybesMinimumDays(
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
    public function setGroupVybesMaximumDays(
        ?int $value,
        ?bool $active
    ) : void;
}