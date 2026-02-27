<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Language implements ValidationRule
{
    protected $language; 
    
    public function __construct($language) {
        $this->language = $language; 
    } 
    
    public function passes($attribute, $value) {
        // Check if the value contains Arabic characters 
        
        if ($this->language == 'ar') {
            return preg_match('/[\x{0600}-\x{06FF}]/u', $value); 
        } 
        
        if ($this->language == 'en') { 
            return preg_match('/^[\p{L}\p{N}]*$/u', $value); 
        }
        // Add more language checks as needed for other languages
         
        return false; 
    } 
    
    public function message() { 
        return __('The :attribute must be in ' . $this->language . '.');
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
}
