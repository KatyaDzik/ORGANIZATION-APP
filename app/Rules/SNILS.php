<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SNILS implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $result = false;
        $snils = (string) $value;
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $snils[$i]* (9 - $i);
        }
        $check_digit = 0;
        if ($sum < 100) {
            $check_digit = $sum;
        } elseif ($sum > 101) {
            $check_digit = $sum % 101;
            if ($check_digit === 100) {
                $check_digit = 0;
            }
        }
        if ($check_digit === (int) substr($snils, -2)) {
            $result = true;}
        return $result;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Неправильное контрольное число СНИЛС';
    }
}
