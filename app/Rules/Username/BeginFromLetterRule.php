<?php

namespace App\Rules\Username;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Class BeginFromLetterRule
 *
 * @package App\Rules\Username
 */
class BeginFromLetterRule implements ValidationRule
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
        if (preg_match(/** @lang text */ '/^[a-zA-Z]{1}.*$/', $value) == 0) {
            $fail(trans('rules/username/beginFromLetter.result.error'));
        }
    }
}
