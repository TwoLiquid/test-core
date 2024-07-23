<?php

namespace App\Settings\Vybe\Interfaces;

/**
 * Interface DisableVybeCreationSettingInterface
 *
 * @package App\Settings\Vybe\Interfaces
 */
interface DisableVybeInteractionTypesSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getVoiceChat() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getVideoChat() : ?bool;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return bool|null
     */
    public function getRealLife() : ?bool;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setVoiceChat(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setVideoChat(
        bool $value
    ) : void;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param bool $value
     */
    public function setRealLife(
        bool $value
    ) : void;
}