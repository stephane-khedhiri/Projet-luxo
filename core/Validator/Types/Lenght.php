<?php

namespace Core\Validator\Types;

use Exception;

class Lenght
{
    /**
     * @var string
     */
    static protected $message = "%s doit contenir entre %s et %s caractère !";


    /**
     * @param string $value
     * @param string $min
     * @param string $max
     * @return void
     * @throws Exception
     */
    static public function Lenght(string $value, string $min, string $max){
        if (strlen($value) < $min || strlen($value) > $max){
            throw new Exception(sprintf(self::$message, $value, $min,$max));
        }
    }
}