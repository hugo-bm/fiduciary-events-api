<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CnpjRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
         if (!$this->isValidCnpj($value)) {
            $fail('The :attribute is not a valid CNPJ.');
        }
    }

    private function isValidCnpj(string $cnpj): bool
    {
        if (strlen($cnpj) !== 14) return false;

        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;

        for ($t = 12; $t < 14; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cnpj[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$c] != $d) return false;
        }

        return true;
    }
}
