<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class ReachableURL implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $result = Http::get($value);
        } catch (RequestException) {
            $fail('The domain does not exist.');
        }

        if($result->failed()) {
            $fail('The :attribute must be reachable.');
        }
    }
}
