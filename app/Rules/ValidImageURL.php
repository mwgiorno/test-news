<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidImageURL implements ValidationRule
{
    const VALID_WIDTH = 1920;
    const VALID_HEIGHT = 1080;
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $imageSize = @getimagesize($value);
        if(!$imageSize) {
            $fail('The :attribute is not a valid image.');
        }

        list($width, $height) = $imageSize;

        if($width < static::VALID_WIDTH && $height < static::VALID_HEIGHT) {
            $fail('The :attribute has invalid image dimensions.');
        }
    }
}
