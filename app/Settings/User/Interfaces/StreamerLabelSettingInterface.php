<?php

namespace App\Settings\User\Interfaces;

/**
 * Interface StreamerLabelSettingInterface
 *
 * @package App\Settings\User\Interfaces
 */
interface StreamerLabelSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getTwitch() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getYoutube() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getFacebook() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getTrovo() : ?int;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     */
    public function setTwitch(
        int $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     */
    public function setYoutube(
        int $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     */
    public function setFacebook(
        int $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     */
    public function setTrovo(
        int $value
    ) : void;
}