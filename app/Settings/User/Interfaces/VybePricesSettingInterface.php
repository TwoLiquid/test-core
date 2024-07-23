<?php

namespace App\Settings\User\Interfaces;

/**
 * Interface VybePricesSettingInterface
 *
 * @package App\Settings\User\Interfaces
 */
interface VybePricesSettingInterface
{
    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getVoiceChatMinimum() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getVoiceChatMaximum() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getVideoChatMinimum() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getVideoChatMaximum() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getRealLifeMinimum() : ?int;

    /**
     * This method provides getting data
     * by related entity repository
     *
     * @return int|null
     */
    public function getRealLifeMaximum() : ?int;

    /**
     * This method provides updating data
     * by related entity repository
     *
     * @param int $value
     * @param bool|null $active
     */
    public function setVoiceChatMinimum(
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
    public function setVoiceChatMaximum(
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
    public function setVideoChatMinimum(
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
    public function setVideoChatMaximum(
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
    public function setRealLifeMinimum(
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
    public function setRealLifeMaximum(
        int $value,
        ?bool $active
    ) : void;
}