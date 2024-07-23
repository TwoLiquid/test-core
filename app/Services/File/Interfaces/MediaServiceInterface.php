<?php

namespace App\Services\File\Interfaces;

/**
 * Interface MediaServiceInterface
 *
 * @package App\Services\File\Interfaces
 */
interface MediaServiceInterface
{
    /**
     * This method provides validating data
     *
     * @param string $content
     * @param string $mime
     */
    public function validateUserAvatar(
        string $content,
        string $mime
    ) : void;

    /**
     * This method provides validating data
     *
     * @param string $content
     * @param string $mime
     */
    public function validateUserBackground(
        string $content,
        string $mime
    ) : void;

    /**
     * This method provides validating data
     *
     * @param string $content
     * @param string $mime
     */
    public function validateUserImage(
        string $content,
        string $mime
    ) : void;

    /**
     * This method provides validating data
     *
     * @param string $content
     * @param string $mime
     */
    public function validateUserVideo(
        string $content,
        string $mime
    ) : void;

    /**
     * This method provides validating data
     *
     * @param string $content
     * @param string $mime
     */
    public function validateUserVoiceSample(
        string $content,
        string $mime
    ) : void;

    /**
     * This method provides validating data
     *
     * @param string $content
     * @param string $mime
     */
    public function validateUserIdVerificationImage(
        string $content,
        string $mime
    ) : void;

    /**
     * This method provides validating data
     *
     * @param string $content
     * @param string $mime
     */
    public function validateAlertImage(
        string $content,
        string $mime
    ) : void;

    /**
     * This method provides validating data
     *
     * @param string $content
     * @param string $mime
     */
    public function validateAlertSound(
        string $content,
        string $mime
    ) : void;

    /**
     * This method provides validating data
     *
     * @param string $content
     * @param string $mime
     */
    public function validateVybeImage(
        string $content,
        string $mime
    ) : void;

    /**
     * This method provides validating data
     *
     * @param string $content
     * @param string $mime
     */
    public function validateVybeVideo(
        string $content,
        string $mime
    ) : void;

    /**
     * This method provides validating data
     *
     * @param string $content
     * @param string $mime
     */
    public function validateActivityImage(
        string $content,
        string $mime
    ) : void;
}