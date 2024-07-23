<?php

namespace App\Transformers\Api\Admin\User\Setting;

use App\Transformers\BaseTransformer;

/**
 * Class UserSettingPageTransformer
 *
 * @package App\Transformers\Api\Admin\User\Setting
 */
class UserSettingPageTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected array $settings;

    /**
     * UserSettingPageTransformer constructor
     *
     * @param array $settings
     */
    public function __construct(
        array $settings
    )
    {
        /** @var array settings */
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function transform() : array
    {
        return [
            'settings' => $this->settings
        ];
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'user_setting_page';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'user_setting_pages';
    }
}
