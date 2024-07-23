<?php

namespace App\Services\File;

use App\Exceptions\BaseException;
use App\Services\File\Interfaces\MediaServiceInterface;

/**
 * Class MediaService
 *
 * @package App\Services\File
 */
class MediaService extends FileService implements MediaServiceInterface
{
    /**
     * @param string $mime
     *
     * @return bool
     */
    public function isVybeImage(
        string $mime
    ) : bool
    {
        if (config('media.custom.vybe.image.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.vybe.image.allowedMimes'))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $mime
     *
     * @return bool
     */
    public function isVybeVideo(
        string $mime
    ) : bool
    {
        if (config('media.custom.vybe.video.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.vybe.video.allowedMimes'))) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateUserAvatar(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.user.avatar.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.user.avatar.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.user.avatar.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateUserBackground(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.user.background.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.user.background.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.user.background.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateUserImage(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.user.image.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.user.image.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.user.image.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateUserVideo(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.user.video.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.user.video.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.user.video.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateUserVoiceSample(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.user.voiceSample.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.user.voiceSample.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.user.voiceSample.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateUserIdVerificationImage(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.user.idVerificationImage.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.user.idVerificationImage.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.user.idVerificationImage.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateAlertImage(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.alert.image.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.alert.image.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.alert.image.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateAlertSound(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.alert.sound.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.alert.sound.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.alert.sound.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateVybeImage(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.vybe.image.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.vybe.image.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.vybe.image.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateVybeVideo(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.vybe.video.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.vybe.video.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.vybe.video.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateActivityImage(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.activity.image.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.activity.image.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.activity.image.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateCategoryIcon(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.category.icon.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.category.icon.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.category.icon.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateDeviceIcon(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.device.icon.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.device.icon.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.device.icon.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validatePlatformIcon(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.platform.icon.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.platform.icon.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.platform.icon.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }

    /**
     * @param string $content
     * @param string $mime
     *
     * @throws BaseException
     */
    public function validateCountryIcon(
        string $content,
        string $mime
    ) : void
    {
        if (config('media.custom.country.icon.allowAllMimes') === false) {
            if (!in_array($mime, config('media.custom.country.icon.allowedMimes'))) {
                throw new BaseException(
                    trans('exceptions/service/media.' . __FUNCTION__ . '.allowedMimes'),
                    null,
                    422
                );
            }
        }

        if ($this->getSizeFromBase64String($content) > config('media.custom.country.icon.maxSize')) {
            throw new BaseException(
                trans('exceptions/service/media.' . __FUNCTION__ . '.maxSize'),
                null,
                422
            );
        }
    }
}