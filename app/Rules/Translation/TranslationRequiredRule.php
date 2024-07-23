<?php

namespace App\Rules\Translation;

use App\Lists\Language\LanguageList;
use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

/**
 * Class TranslationRequiredRule
 *
 * @package App\Rules\Translation
 */
class TranslationRequiredRule implements ValidationRule
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
        if (!in_array(
                LanguageList::getEnglish()->iso,
                array_keys($value)
            ) || is_null($value[LanguageList::getEnglish()->iso])
        ) {
            $fail(trans('rules/translation/required.result.error'));
        }
    }
}
