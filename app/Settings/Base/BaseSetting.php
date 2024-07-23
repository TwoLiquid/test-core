<?php

namespace App\Settings\Base;

use App\Settings\Base\Interfaces\BaseSettingInterface;

/**
 * Class BaseSetting
 *
 * @package App\Settings\Base
 */
abstract class BaseSetting implements BaseSettingInterface
{
    /**
     * Setting identity code
     *
     * @var string
     */
    protected string $code = '';

    //--------------------------------------------------------------------------
    // Getters

    /**
     * @return string
     */
    public function getCode() : string
    {
        return $this->code;
    }
    /**
     * Processing field building method
     *
     * @param bool $customsOnly
     * @param array $field
     * @param $value
     *
     * @return array|null
     */
    protected function processField(
        bool $customsOnly,
        array $field,
        $value
    ) : ?array
    {
        if ($customsOnly === true) {
            if ($field['custom'] === false) {
                return null;
            }
        }

        if (isset($field['original_value'])) {
            unset($field['original_value']);
        }

        return $field + [
            'value' => $value
        ];
    }
}