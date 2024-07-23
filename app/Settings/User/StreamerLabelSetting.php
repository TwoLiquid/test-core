<?php

namespace App\Settings\User;

use App\Exceptions\DatabaseException;
use App\Lists\Setting\Type\SettingTypeList;
use App\Settings\Base\UserSetting;
use App\Settings\User\Interfaces\StreamerLabelSettingInterface;

/**
 * Class StreamerLabelSetting
 *
 * @package App\Settings\User
 */
class StreamerLabelSetting extends UserSetting implements StreamerLabelSettingInterface
{
    /**
     * Setting name
     *
     * @var string
     */
    protected string $name = 'Streamer label';

    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = 'streamer_label';

    /**
     * Setting description
     *
     * @var string
     */
    protected string $description = 'Users will automatically receive a streamer label upon receiving the indicated number of followers';

    //--------------------------------------------------------------------------
    // Fields

    /**
     * @var array
     */
    protected array $twitch = [
        'code'           => 'twitch',
        'name'           => 'Twitch',
        'type'           => 'integer',
        'icon'           => 'twitch',
        'custom'         => false,
        'original_value' => 200
    ];

    /**
     * @var array
     */
    protected array $youtube = [
        'code'           => 'youtube',
        'name'           => 'Youtube',
        'type'           => 'integer',
        'icon'           => 'youtube',
        'custom'         => false,
        'original_value' => 200
    ];

    /**
     * @var array
     */
    protected array $facebook = [
        'code'           => 'facebook',
        'name'           => 'Facebook',
        'type'           => 'integer',
        'icon'           => 'facebook',
        'custom'         => false,
        'original_value' => 200
    ];

    /**
     * @var array
     */
    protected array $trovo = [
        'code'           => 'trovo',
        'name'           => 'Trovo',
        'type'           => 'integer',
        'icon'           => 'trovo',
        'custom'         => false,
        'original_value' => 200
    ];

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getTwitch() : ?int
    {
        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->twitch['code']
        );

        /**
         * Checking default user setting existence
         */
        if ($userSetting) {
            return (int) $userSetting->value;
        }

        return null;
    }

    /**
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getYoutube() : ?int
    {
        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->youtube['code']
        );

        /**
         * Checking default user setting existence
         */
        if ($userSetting) {
            return (int) $userSetting->value;
        }

        return null;
    }

    /**
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getFacebook() : ?int
    {
        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->facebook['code']
        );

        /**
         * Checking default user setting existence
         */
        if ($userSetting) {
            return (int) $userSetting->value;
        }

        return null;
    }

    /**
     * @return int|null
     *
     * @throws DatabaseException
     */
    public function getTrovo() : ?int
    {
        /**
         * Getting default user setting
         */
        $userSetting = $this->userSettingRepository->findByCodeDefault(
            $this->code,
            $this->trovo['code']
        );

        /**
         * Checking default user setting existence
         */
        if ($userSetting) {
            return (int) $userSetting->value;
        }

        return null;
    }

    //--------------------------------------------------------------------------
    // Setters

    /**
     * @param int $value
     *
     * @throws DatabaseException
     */
    public function setTwitch(
        int $value
    ) : void
    {
        /**
         * Updating user setting
         */
        $this->userSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->twitch['code'],
            $value
        );
    }

    /**
     * @param int $value
     *
     * @throws DatabaseException
     */
    public function setYoutube(
        int $value
    ) : void
    {
        /**
         * Updating user setting
         */
        $this->userSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->youtube['code'],
            $value
        );
    }

    /**
     * @param int $value
     *
     * @throws DatabaseException
     */
    public function setFacebook(
        int $value
    ) : void
    {
        /**
         * Updating user setting
         */
        $this->userSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->facebook['code'],
            $value
        );
    }

    /**
     * @param int $value
     *
     * @throws DatabaseException
     */
    public function setTrovo(
        int $value
    ) : void
    {
        /**
         * Updating user setting
         */
        $this->userSettingRepository->updateValueByCodeDefault(
            $this->code,
            $this->trovo['code'],
            $value
        );
    }

    //--------------------------------------------------------------------------
    // Build

    /**
     * @param bool|null $customOnly
     *
     * @return array|null
     *
     * @throws DatabaseException
     */
    public function build(
        ?bool $customOnly = false
    ) : ?array
    {
        $data = [
            'code'     => $this->code,
            'name'     => $this->name,
            'children' => array_filter([
                $this->processField(
                    $customOnly,
                    $this->twitch,
                    $this->getTwitch()
                ),
                $this->processField(
                    $customOnly,
                    $this->youtube,
                    $this->getYoutube()
                ),
                $this->processField(
                    $customOnly,
                    $this->facebook,
                    $this->getFacebook()
                ),
                $this->processField(
                    $customOnly,
                    $this->trovo,
                    $this->getTrovo()
                )
            ])
        ];

        if (empty($data['children'])) {
            return null;
        }

        return $data;
    }

    //--------------------------------------------------------------------------
    // Seed

    /**
     * @throws DatabaseException
     */
    public function seed() : void
    {
        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->twitch['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->twitch['code'],
                $this->twitch['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->youtube['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->youtube['code'],
                $this->youtube['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->facebook['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->facebook['code'],
                $this->facebook['original_value'],
                true
            );
        }

        /**
         * Checking default user setting existence
         */
        if (!$this->userSettingRepository->existsByCodeDefault(
            $this->code,
            $this->trovo['code']
        )) {

            /**
             * Creating default user setting
             */
            $this->userSettingRepository->store(
                SettingTypeList::getDefault(),
                null,
                $this->code,
                $this->trovo['code'],
                $this->trovo['original_value'],
                true
            );
        }
    }
}