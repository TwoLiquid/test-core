<?php

namespace App\Rules\Username;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Class AlphanumericRule
 *
 * @package App\Rules\Username
 */
class AlphanumericRule implements ValidationRule
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
        if (preg_match('/^[a-zA-Z0-9_]+$/', $value) == 0) {
            $fail(trans('rules/username/alphanumeric.result.error'));
        }
    }
}
