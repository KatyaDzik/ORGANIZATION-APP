<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OGRN implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $err;
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
        $response = file_get_contents('https://htmlweb.ru/json/validator/ogrn/'.$value);
        $phpArray = json_decode($response, true);
        if ($phpArray['status']==200) {
            return true;
        }
        else{
            $this->err = $phpArray['error'];
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->err;
    }
}
