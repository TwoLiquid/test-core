<?php

namespace App\Rules\Translation;

use App\Lists\Language\LanguageList;
use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

/**
 * Class TranslationAcceptableRule
 *
 * @package App\Rules\Translation
 */
class TranslationAcceptableRule implements ValidationRule
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
        if (!empty(array_diff(array_keys($value),
            LanguageList::getTranslatableItems()
                ->pluck('iso')
                ->values()
                ->toArray()
        ))) {
            $fail(trans('rules/translation/acceptable.result.error'));
        }
    }
}
