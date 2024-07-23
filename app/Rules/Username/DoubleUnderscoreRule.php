<?php

namespace App\Rules\Username;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Class DoubleUnderscoreRule
 *
 * @package App\Rules\Username
 */
class DoubleUnderscoreRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     *
     * @return void
     */
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ) : void
    {
        if (preg_match('/^(?!.*__).*$/', $value) == 0) {
            $fail(trans('rules/username/doubleUnderscore.result.error'));
        }
    }
}
